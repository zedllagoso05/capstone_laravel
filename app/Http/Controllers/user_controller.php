<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Unique;
use App\Models\Evaluation;
use App\Models\Certificate;
use App\Models\GroupCertificate;
use App\Models\GroupMilestones;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use App\Models\Rubric;
use App\Models\Milestone;
use App\Models\RubricCriteria;
use App\Models\Student;
use App\Models\Group;
use App\Models\TeamMember;
use Illuminate\Contracts\Support\ValidatedData;
use App\Models\Teacher;
use App\Models\Admin;
use App\Models\EvaluationRoom;
use App\Models\Section;
use App\Models\CapstoneStages;
use Illuminate\Support\Facades\Hash;
use App\Imports\StudentsImport;
use App\Imports\TeachersImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon; // Add this at the top of your controller
use App\Services\Mailer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class user_controller extends Controller
{
    // ── ROLE REDIRECT HELPER ──────────────────────────────────────
    private function redirectByRole()
    {
        return match(Auth::user()->role) {
            'admin'   => redirect()->route('admin.page'),
            'teacher' => redirect()->route('teacher.page'),
            default   => redirect()->route('student.page'),
        };
    }

    // ── REGISTER ──────────────────────────────────────────────────
    public function register(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        $user = User::where('user_id', session('user_id'))->first();

        if (!$user) {
            return back()->withErrors(['id' => 'No matching ID found. Please check your ID again.']);
        }

        if (!is_null($user->password)) {
            return back()->withErrors(['id' => 'This account has already been registered. Please log in instead.']);
        }

        $incomingdata = $request->validate([
            'name'     => ['required', 'min:3'],
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user->name     = $incomingdata['name'];
        $user->email    = $incomingdata['email'];
        $user->password = bcrypt($incomingdata['password']);
        $user->save();

        Auth::login($user);

        // ── GENERATE & SEND EMAIL VERIFICATION CODE AFTER CREATING ACCOUNT ──
        $code = (string) random_int(100000, 999999);
        Cache::put('verify_code_' . $user->user_id, $code, now()->addMinutes(10));

        Mailer::send(
            $user->email,
            $user->user_id,
            'Your Capstone Tracker verification code',
            "<p>Your verification code is:</p><h2>{$code}</h2><p>This code expires in 10 minutes.</p>"
        );

        return redirect()->route('verification.notice')
            ->with('success', 'A verification code has been sent to your email.')
            ->with('code_sent', true)
            ->with('verified_email', $user->email);
    }

    // ── VERIFICATION ─────────────────────────────────────────────
    public function sendVerificationCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('user_id', session('user_id'))->first();
        if (!$user) {
            return back()->withErrors(['id' => 'No matching ID found. Please check your ID again.']);
        }

        if (!is_null($user->password)) {
            return back()->withErrors(['id' => 'This account has already been registered. Please log in instead.']);
        }

        // Find the email already on file for this role — this is the identity check.
        $onFileEmail = match ($user->role) {
            'student' => Student::where('user_id', $user->user_id)->value('student_email'),
            'teacher' => Teacher::where('user_id', $user->user_id)->value('teacher_email'),
            'admin'   => Admin::where('user_id', $user->user_id)->value('admin_email'),
            default   => null,
        };

        if (!$onFileEmail || strtolower($onFileEmail) !== strtolower($request->email)) {
            return back()->withErrors(['email' => 'This email does not match our records for this ID.']);
        }

        $code = (string) random_int(100000, 999999);
        Cache::put('verify_code_' . $user->user_id, $code, now()->addMinutes(10));

        $sent = Mailer::send(
            $onFileEmail,
            $user->user_id,
            'Your Capstone Tracker verification code',
            "<p>Your verification code is:</p><h2>{$code}</h2><p>This code expires in 10 minutes.</p>"
        );

        if (!$sent) {
            return back()->withErrors(['email' => 'Failed to send verification email. Please try again.']);
        }

        return back()->with('success', 'A verification code has been sent to your email.')
                      ->with('code_sent', true)
                      ->with('verified_email', $request->email);
    }

    // ── LOGIN ─────────────────────────────────────────────────────
    public function login(Request $request)
    {
        // Block if already logged in
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        if (!session('user_id')) {
            return redirect('/')->withErrors(['id' => 'Please enter your ID first.']);
        }

        $incomingdata = $request->validate([
            'logname'     => 'required',
            'logpassword' => 'required|min:6'
        ]);

        if (Auth::attempt([
            'user_id'     => session('user_id'),
            'name'        => $incomingdata['logname'],
            'password'    => $incomingdata['logpassword']
        ])) {
            $request->session()->regenerate();
            return $this->redirectByRole(); // ← goes to correct dashboard by role
        }

        return back()->withErrors([
            'logname' => 'The provided credentials do not match our records.',
        ]);
    }

    // ── LOGOUT ───────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget('user_id');  // ← clears home page session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // ── DESTROY SESSION (Change User) ────────────────────────────
    public function destroy(Request $request)
    {
        Auth::logout();                          // ← also clears auth
        $request->session()->forget('user_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // ── ID CHECK ─────────────────────────────────────────────────
    public function id(Request $request)
    {
        $request->validate(['id' => 'required']);

        $user = User::where('user_id', $request->id)->first();

        if (!$user) {
            return back()->withErrors(['id' => 'User ID not found.']);
        }

        session(['user_id' => $request->id]);
        return redirect('/');
    }
    
    /**
     * If a certificate is configured for this milestone, issue it to the group
     * (idempotent — won't create a duplicate if it's already been issued).
     */
    private function autoIssueCertificateIfEligible($groupId, $milestoneId)
    {
        $certificate = Certificate::where('milestone_id', $milestoneId)->first();
        if (!$certificate) {
            return;
        }

        GroupCertificate::firstOrCreate(
            ['group_id' => $groupId, 'certificate_id' => $certificate->id],
            ['issued_date' => now()->toDateString()]
        );
    }
    // ── STUDENT DASHBOARD ─────────────────────────────────────────
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user) return redirect('/');
        if ($user->role !== 'student') {
            return $this->redirectByRole();
        }

        $student = Student::where('user_id', $user->user_id)->first();
        $groups = $student?->groups()->first();
        $members = collect();
        $adviser = Teacher::where('user_id', $groups?->adviser_id)->first();
        $completedMilestoneIds = [];
        $remarksByMilestone = collect();
        $absencesByMilestone = collect();
        if ($groups) {
            $remarksByMilestone = \App\Models\Remarks::where('group_id', $groups->id)
                ->get()
                ->keyBy('milestone_id');

            $absencesByMilestone = \App\Models\Absence::where('group_id', $groups->id)
                ->get()
                ->groupBy('milestone_id');
        }

        if ($groups) {
            $groups->load(['team_members.student.user', 'adviser.user', 'groupMilestones']);
            $members = $groups->team_members;
            $adviser = $groups->adviser;
            $completedMilestoneIds = $groups->groupMilestones
                ->where('status', 'completed')
                ->pluck('milestone_id')
                ->toArray();
        }

        $enabledStageIds = CapstoneStages::where('is_enabled', true)->pluck('id');
        $milestones = Milestone::whereIn('capstone_stage_id', $enabledStageIds)->orderBy('step_order')->get();
        $overallProgress = 0;
        $nextMilestone = null;

        if ($groups) {
            $enabledMilestoneIds = $milestones->pluck('id')->toArray();
            $completedMilestoneIds = $groups->groupMilestones
                ->where('status', 'completed')
                ->whereIn('milestone_id', $enabledMilestoneIds)
                ->pluck('milestone_id')
                ->toArray();
            $completed = count($completedMilestoneIds);
            $overallProgress = $milestones->count() ? round(($completed / $milestones->count()) * 100) : 0;
            // Next = first milestone (by step order) not yet completed for this group
            $nextMilestone = $milestones->first(fn($m) => !in_array($m->id, $completedMilestoneIds));
        }

        $evaluations = Evaluation::where('group_id', $groups?->id)
            ->with('milestone', 'teacher.user')
            ->latest('evaluation_date')
            ->limit(5)
            ->get();
            
        $groupcertificates = $groups
            ? GroupCertificate::where('group_id', $groups->id)->get()
            : collect();

            // in dashboard(), replace the certificates map with:
            $certificates = Certificate::all()->map(function ($cert) use ($groupcertificates, $groups) {
                $groupCert = $groupcertificates->firstWhere('certificate_id', $cert->id);
                $cert->unlocked = (bool) $groupCert;
                $cert->issued_date = $groupCert->issued_date ?? null;
                return $cert;
            });


        return view('sections.student', compact(
            'user', 'student', 'groups', 'members', 'adviser',
            'milestones', 'overallProgress', 'nextMilestone',
            'evaluations', 'certificates', 'completedMilestoneIds', 'groupcertificates',
            'remarksByMilestone', 'absencesByMilestone'
        ));
    }

    // ── ADMIN DASHBOARD ──────────────────────────────────────────
    public function adminDashboard()
    {
        $user = Auth::user();
        if (!$user) return redirect('/');
        if ($user->role !== 'admin') {
            return $this->redirectByRole();
        }
        $admin = Admin::where('user_id', $user->user_id)->first();
        // ── Core data ──
        $enabledStageIds = CapstoneStages::where('is_enabled', true)->pluck('id');
        $milestones = Milestone::whereIn('capstone_stage_id', $enabledStageIds)->with('rubrics')->orderBy('step_order')->get();
        $rubrics = Rubric::whereHas('milestone', function($q) use ($enabledStageIds) {
            $q->whereIn('capstone_stage_id', $enabledStageIds);
        })->with(['milestone', 'criteria'])->latest()->get();
        $groups = Group::where('is_archived', false)->with(['students', 'groupMilestones'])->get();
        $archivedGroups = Group::where('is_archived', true)->with(['adviser', 'section', 'team_members'])->get();
        $totalGroups = $groups->count();

        $allTeachers = Teacher::where('is_archived', false)->with(['groups', 'user', 'sections'])->get();
        $totalTeachers = $allTeachers->count();

        $sections = Section::where('is_archived', false)->get();
        $totalSections = $sections->count();
        $allSections= Section:: all();
        // ── Students with their group (singular) ──
        $allStudents = Student::where('is_archived', false)->with(['user', 'groups'])->get();
        $totalStudents = $allStudents->count();

        // evaluationroom
        $evaluationRooms = EvaluationRoom::with(['panelists', 'groups', 'requiredMilestone'])->latest()->get();

        // ── Section Progress ──
        $sectionProgress = [];
        $milestoneCount = $milestones->count();

        foreach ($sections as $section) {
            // Get students in this section
            $studentUserIds = Student::where('section', $section->section_name)->pluck('user_id');
            // Get group IDs of those students
            $groupIds = TeamMember::whereIn('user_id', $studentUserIds)->pluck('group_id')->unique();
            // Get groups with their milestones
            $groupsInSection = Group::where('is_archived', false)->whereIn('id', $groupIds)->with('groupMilestones')->get();

            $total = $groupsInSection->count();
            $done = 0;
            $inProgress = 0;
            $notStarted = 0;
            $totalProgress = 0;

            $enabledMilestoneIds = $milestones->pluck('id')->toArray();

            foreach ($groupsInSection as $group) {
                $completed = $group->groupMilestones
                    ->where('status', 'completed')
                    ->whereIn('milestone_id', $enabledMilestoneIds)
                    ->count();
                $progress = $milestoneCount > 0 ? ($completed / $milestoneCount) * 100 : 0;
                $totalProgress += $progress;

                if ($completed == $milestoneCount) {
                    $done++;
                } elseif ($completed > 0) {
                    $inProgress++;
                } else {
                    $notStarted++;
                }
            }

            $avg = $total > 0 ? round($totalProgress / $total) : 0;

            $sectionProgress[] = (object) [
                'name'        => $section->section_name,
                'done'        => $done,
                'in_progress' => $inProgress,
                'not_started' => $notStarted,
                'avg'         => $avg,
            ];
        }

        // ── Milestone Completion ──
        $milestoneCompletion = [];
        $colors = ['#d6b15c', '#b88d3a', '#8b6914', '#5b6375', '#0a1428']; // dashboard palette

        foreach ($milestones->values() as $i => $m) {
            $completedCount = GroupMilestones::where('milestone_id', $m->id)
                                            ->where('status', 'completed')
                                            ->count();
            $milestoneCompletion[] = (object) [
                'name'      => $m->milestone_title,
                'completed' => $completedCount,
                'total'     => $totalGroups,
                'color'     => $colors[$i % count($colors)],
                'stage'     => $m->capstone_stage_id,
            ];
        }

        // ── Overall Stats ──
        $onTrackCount = 0;
        $atRiskCount  = 0;
        $delayedCount = 0;
        $totalProgressSum = 0;

        $enabledMilestoneIds = $milestones->pluck('id')->toArray();

        foreach ($groups as $group) {
            $completed = $group->groupMilestones
                ->where('status', 'completed')
                ->whereIn('milestone_id', $enabledMilestoneIds)
                ->count();
            $progress = $milestoneCount > 0 ? ($completed / $milestoneCount) * 100 : 0;
            $totalProgressSum += $progress;
            if ($progress >= 70) {
                $onTrackCount++;
            } elseif ($progress >= 40) {
                $atRiskCount++;
            } else {
                $delayedCount++;
            }
        }
        $avgProgress = $totalGroups > 0 ? round($totalProgressSum / $totalGroups) : 0;

        // ── Capstone Completion Progress (real data, per milestone) ──
        $progressItems = [];
        $progressColors = ['#d6b15c', '#b88d3a', '#8b6914', '#5b6375', '#0a1428'];
        foreach ($milestones->take(5) as $i => $m) {
            $completedCount = GroupMilestones::where('milestone_id', $m->id)
                ->where('status', 'completed')
                ->count();
            $pct = $totalGroups > 0 ? round(($completedCount / $totalGroups) * 100) : 0;

            $progressItems[] = (object) [
                'label' => $m->milestone_title,
                'done'  => $completedCount,
                'total' => $totalGroups,
                'pct'   => $pct,
                'color' => $progressColors[$i % count($progressColors)],
            ];
        }

        // ── Sections Data for Assign Modal ──
        $sectionsWithTeachers = Section::leftJoin('teachers', 'sections.user_id', '=', 'teachers.user_id')
            ->select('sections.*', 'teachers.teacher_first_name', 'teachers.teacher_last_name')
            ->get();

        $groupsData = Group::where('is_archived', false)->with(['adviser', 'section', 'team_members', 'room'])->get()->map(function ($group) {
            return [
                'id'                    => $group->id,
                'name'                  => $group->group_name,
                'capstone_title'        => $group->capstone_title,
                'section_id'            => $group->section_id,
                'section_name'          => $group->section->section_name ?? 'N/A',
                'assigned_teacher_id'   => $group->adviser->user_id ?? null,
                'assigned_teacher_name' => $group->adviser
                    ? $group->adviser->teacher_first_name . ' ' . $group->adviser->teacher_last_name
                    : null,
                'member_count'          => $group->team_members->count(),
                'room_id'               => $group->room_id,
                'room_name'             => $group->room->room_name ?? 'Unassigned',
            ];
        });

        $sectionsData = $sectionsWithTeachers->map(function($section) {
            return [
                'id'                     => $section->id,
                'name'                   => $section->section_name,
                'assigned_teacher_id'    => $section->user_id,
                'assigned_teacher_name'  => $section->user_id
                    ? $section->teacher_first_name . ' ' . $section->teacher_last_name
                    : null,
            ];
        });

        $capstoneStages = CapstoneStages::where('is_archived', false)->get();

        // ── Recent Activities (real data) ──
        $recentActivities = collect();

        foreach (Rubric::latest()->take(5)->get() as $rubric) {
            $recentActivities->push([
                'icon' => 'fa-check', 'color' => '#1e6b3a',
                'title' => 'New rubric created',
                'subtitle' => $rubric->rubric_name,
                'timestamp' => $rubric->created_at,
            ]);
        }

        foreach (Evaluation::with('group', 'milestone')->latest()->take(5)->get() as $eval) {
            $recentActivities->push([
                'icon' => 'fa-check', 'color' => '#1e6b3a',
                'title' => 'Group evaluated',
                'subtitle' => ($eval->group->group_name ?? 'Group') . ' — ' . ($eval->milestone->milestone_title ?? 'Milestone'),
                'timestamp' => $eval->created_at,
            ]);
        }

        foreach (Student::latest()->take(5)->get() as $student) {
            $recentActivities->push([
                'icon' => 'fa-arrow-right', 'color' => '#0a1428',
                'title' => 'Student registered',
                'subtitle' => $student->student_first_name . ' ' . $student->student_last_name . ' (' . $student->user_id . ')',
                'timestamp' => $student->created_at,
            ]);
        }

        foreach (Teacher::latest()->take(5)->get() as $teacher) {
            $recentActivities->push([
                'icon' => 'fa-arrow-right', 'color' => '#0a1428',
                'title' => 'Teacher added',
                'subtitle' => $teacher->teacher_first_name . ' ' . $teacher->teacher_last_name,
                'timestamp' => $teacher->created_at,
            ]);
        }

        $upcoming = Milestone::whereBetween('due_date', [now(), now()->addDays(3)])->get();
        foreach ($upcoming as $m) {
            $pendingCount = Group::where('is_archived', false)->whereDoesntHave('groupMilestones', function ($q) use ($m) {
                $q->where('milestone_id', $m->id)->where('status', 'completed');
            })->count();
            if ($pendingCount > 0) {
                $recentActivities->push([
                    'icon' => 'fa-clock', 'color' => '#8a5d0b',
                    'title' => 'Deadline reminder',
                    'subtitle' => $m->milestone_title . ' due for ' . $pendingCount . ' group(s)',
                    'timestamp' => now()->subMinute(), // keep it near top, not stale
                ]);
            }
        }

        $recentActivities = $recentActivities->sortByDesc('timestamp')->take(8)->values();

        return view('sections.admin', compact(
            'user',
            'milestones',
            'rubrics',
            'sectionProgress',
            'milestoneCompletion',
            'progressItems',
            'onTrackCount',
            'atRiskCount',
            'delayedCount',
            'avgProgress',
            'admin',
            'allTeachers',
            'allStudents',
            'allSections',
            'recentActivities',
            'evaluationRooms',
            'sections',
            'groupsData',
            'sectionsData',
            'totalStudents',
            'totalGroups',
            'totalTeachers',
            'totalSections',
            'capstoneStages',
            'archivedGroups'
        ));
    }

    // ── ASSIGN / CHANGE ADVISER ────────────────────────────────────
    public function assignGroups(Request $request)
    {
        $validated = $request->validate([
            'group_id'   => 'required|exists:groups,id',
            'adviser_id' => 'required|exists:teachers,id',
        ]);

        Group::where('id', $validated['group_id'])->update(['adviser_id' => $validated['adviser_id']]);

        return back()->with('success', 'Adviser changed successfully.');
    }

    // ── ASSIGN / CHANGE SECTION TEACHER ────────────────────────────
    public function assignSection(Request $request)
    {
        $validated = $request->validate([
            'section_id'      => 'required|exists:sections,id',
            'teacher_user_id' => 'required|string',
        ]);

        $section = Section::findOrFail($validated['section_id']);

        if ($validated['teacher_user_id'] === 'none') {
            $section->user_id = null;
        } else {
            $teacherExists = Teacher::where('user_id', $validated['teacher_user_id'])->exists();
            if (!$teacherExists) {
                return back()->withErrors(['teacher_user_id' => 'The selected teacher does not exist.']);
            }
            $section->user_id = $validated['teacher_user_id'];
        }

        $section->save();

        return back()->with('success', 'Section assigned successfully.');
    }

    public function getTeacherGroups($teacherId)
    {
        $teacher = Teacher::where('user_id', $teacherId)->first();
        if (!$teacher) return response()->json([]);

        return response()->json(Group::where('adviser_id', $teacher->id)->pluck('id'));
    }

    // ── TEACHER DASHBOARD ─────────────────────────────────────────
   public function teacherDashboard()
{
    $user = Auth::user();
    if (!$user) return redirect('/');
    if ($user->role !== 'teacher') {
        return $this->redirectByRole();
    }

    $teacher = Teacher::where('user_id', $user->user_id)->first();
    if (!$teacher) {
        return redirect('/')->with('error', 'Teacher profile not found.');
    }

    $assignedRooms = $teacher->evaluationRooms()->with('groups')->get();
    $assignedRoomIds = $assignedRooms->pluck('id');

    // ── All evaluation rooms, for the classroom grid ──
    $allRooms = EvaluationRoom::with(['panelists', 'groups'])->get();

    $groups = Group::where('is_archived', false)
        ->where(function($query) use ($teacher, $assignedRoomIds) {
            $query->where('adviser_id', $teacher->id)
                  ->orWhereIn('room_id', $assignedRoomIds);
        })
        ->with(['students', 'groupMilestones', 'team_members', 'room'])
        ->get();

    $totalGroups = $groups->count();
    $teacherSections = $teacher->sections;
    $sectionIdsWithGroups = $groups->pluck('section_id')->filter()->unique();
    $sectionsWithGroups = Section::whereIn('id', $sectionIdsWithGroups)->get();
    $totalStudents = $groups->flatMap(fn($g) => $g->students)->unique('id')->count();

    $enabledStageIds = CapstoneStages::where('is_enabled', true)->pluck('id');
    $milestones = Milestone::whereIn('capstone_stage_id', $enabledStageIds)->orderBy('step_order')->get();
    $milestoneCount = $milestones->count();

    $groupProgress = [];
    $enabledMilestoneIds = $milestones->pluck('id')->toArray();

    foreach ($groups as $group) {
        $completed = $group->groupMilestones
            ->where('status', 'completed')
            ->whereIn('milestone_id', $enabledMilestoneIds)
            ->count();
        $progress = $milestoneCount > 0 ? round(($completed / $milestoneCount) * 100) : 0;

        $groupProgress[] = (object) [
            'group_name' => $group->group_name ?? 'Unnamed Group',
            'capstone_title' => $group->capstone_title ?? 'No title',
            'progress' => $progress,
            'completed' => $completed,
            'total' => $milestoneCount,
            'id' => $group->id,
        ];
    }

    $evaluations = Evaluation::where('teacher_id', $teacher->id)
        ->with(['group', 'milestone'])
        ->latest('evaluation_date')
        ->limit(10)
        ->get()
        ->map(function ($e) {
            $decoded = json_decode($e->feedback, true);
            if (is_array($decoded) && isset($decoded['feedback_text'])) {
                $e->feedback = $decoded['feedback_text'];
            }
            return $e;
        });

    $totalEvaluations = Evaluation::where('teacher_id', $teacher->id)->count();

    $pendingEvaluations = Evaluation::where('teacher_id', $teacher->id)
        ->whereNull('score')
        ->count();

    if ($pendingEvaluations == 0) {
        $pendingEvaluations = $groups->filter(function($group) use ($milestoneCount, $enabledMilestoneIds) {
            $completed = $group->groupMilestones
                ->where('status', 'completed')
                ->whereIn('milestone_id', $enabledMilestoneIds)
                ->count();
            return $completed < $milestoneCount;
        })->count();
    }

    $sections = [];
    foreach ($groups as $group) {
        $firstStudent = $group->students->first();
        $sectionName = $firstStudent ? ($firstStudent->section ?? 'Unassigned') : 'Unassigned';
        if (!isset($sections[$sectionName])) {
            $sections[$sectionName] = [];
        }
        $sections[$sectionName][] = $group;
    }

    $allSections = Section::all();
    $allGroups = Group::where('is_archived', false)
        ->where(function($query) use ($teacher, $assignedRoomIds) {
            $query->where('adviser_id', $teacher->id)
                  ->orWhereIn('room_id', $assignedRoomIds);
        })
        ->with(['students', 'groupMilestones'])
        ->get();

    return view('sections.teacher', compact(
        'user', 'teacher', 'groups', 'totalGroups', 'totalStudents',
        'milestones', 'groupProgress', 'evaluations', 'totalEvaluations',
        'pendingEvaluations', 'sections', 'teacherSections', 'allSections',
        'allGroups', 'sectionsWithGroups', 'assignedRooms', 'allRooms'
    ));
}

    // ── GET STUDENTS BY SECTION (unassigned only) ──────────────────
    public function getStudentsBySection($sectionName)
    {
        $students = Student::where('section', $sectionName)
            ->whereDoesntHave('teamMembers')
            ->with('user')
            ->get(['user_id', 'student_first_name', 'student_last_name']); // include user_id
        return response()->json($students);
    }

    // ── CREATE GROUP (admin/teacher shared) ─────────────────────────
    public function createGroup(Request $request)
    {
        $validated = $request->validate([
            'group_name'         => 'required|string|max:255|unique:groups,group_name',
            'capstone_title'     => 'required|string|max:255',
            'section'         => 'required|exists:sections,id',
            'students'           => 'required|array|min:1|max:5',
            'students.*.user_id' => 'exists:students,user_id',
            'students.*.role'    => 'required|string|in:programmer,designer,researcher',
            'adviser'            => 'required|exists:teachers,id'
        ]);

        foreach ($validated['students'] as $studentData) {
            $student = Student::where('user_id', $studentData['user_id'])->first();
            if ($student && $student->teamMembers()->exists()) {
                return back()
                    ->withErrors(['students' => "Student {$student->student_first_name} {$student->student_last_name} is already in a group."])
                    ->withInput();
            }
        }

        $group = Group::create([
            'group_name'     => $validated['group_name'],
            'capstone_title' => $validated['capstone_title'],
            'adviser_id'     => $validated['adviser'],
            'section_id'     => $validated['section'],
        ]);

        foreach ($validated['students'] as $studentData) {
            TeamMember::create([
                'group_id' => $group->id,
                'user_id'  => $studentData['user_id'],
                'role'     => $studentData['role'],
            ]);
        }

        $enabledStageIds = CapstoneStages::where('is_enabled', true)->pluck('id');
        foreach (Milestone::whereIn('capstone_stage_id', $enabledStageIds)->get() as $milestone) {
            GroupMilestones::firstOrCreate(
                ['group_id' => $group->id, 'milestone_id' => $milestone->id],
                ['status' => 'pending', 'due_date' => $milestone->due_date]
            );
        }

        return $this->redirectByRole()->with('success', 'Group created successfully!');
    }

    // ── AUTO CHECK SECTIONS ─────────────────────────────────────────
    public function getTeacherSections($teacherId)
    {
        $teacher = Teacher::where('user_id', $teacherId)->first();
        if (!$teacher) {
            return response()->json([]);
        }

        // Return an array of section IDs that this teacher already has
        $sectionIds = $teacher->sections->pluck('id')->toArray();
        return response()->json($sectionIds);
    }

    // ── TEACHER PROFILE UPDATE ──────────────────────────────────────
    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'teacher') {
            return redirect('/');
        }

        $teacher = Teacher::where('user_id', $user->user_id)->first();

        if (!$teacher) {
            return redirect('/')->with('error', 'Teacher profile not found.');
        }

        $validatedData = $request->validate([
            'teacher_first_name' => 'required|string|max:255',
            'teacher_last_name' => 'required|string|max:255',
            'teacher_middle_name' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'teacher_email' => 'required|email|unique:teachers,teacher_email,' . $teacher->id,
        ]);

        $teacher->update($validatedData);

        $teacher->teacher_first_name = $validatedData['teacher_first_name'];
        $teacher->teacher_middle_name = $validatedData['teacher_middle_name'];
        $teacher->teacher_last_name = $validatedData['teacher_last_name'];
        $teacher->contact_number = $validatedData['contact_number'];
        $teacher->teacher_email = $validatedData['teacher_email'];

        $teacher->save();

        return redirect()->route('teacher.page')->with('success', 'Profile updated successfully.');
    }

    // ── ADMIN PROFILE UPDATE ──────────────────────────────────────
    public function adminProfileUpdate(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect('/');
        }

        $admin = Admin::where('user_id', $user->user_id)->first();
        if (!$admin) {
            return redirect('/')->with('error', 'Admin profile not found.');
        }

        $validatedData = $request->validate([
            'admin_first_name'  => 'required|string|max:255',
            'admin_last_name'   => 'required|string|max:255',
            'admin_middle_name' => 'nullable|string|max:255',
            'contact_number'    => 'nullable|string|max:20',
            'admin_email'       => 'required|email|unique:admin,admin_email,' . $admin->id,
        ]);

        $admin->update($validatedData);
        $admin->save();

        return redirect()->route('admin.page')->with('success', 'Profile updated successfully.');
    }

    // ── STUDENT PROFILE UPDATE ──────────────────────────────────────
    public function update(Request $request)
    {
        $user = User::find(Auth::id());
        if (!$user || $user->role !== 'student') {
            return redirect('/');
        }

        $student = Student::where('user_id', $user->user_id)->first();
        if (!$student) {
            return redirect('/')->with('error', 'Student profile not found.');
        }

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:students,student_email,' . $student->id . '|unique:users,email,' . $user->id,
            'phone'      => 'nullable|string|max:20',
        ]);

        $student->student_first_name = $validatedData['first_name'];
        $student->student_last_name  = $validatedData['last_name'];
        $student->student_email      = $validatedData['email'];
        $student->contact_number     = $validatedData['phone'] ?? '';
        $student->save();

        if ($user->email !== $validatedData['email']) {
            $user->email = $validatedData['email'];
            $user->save();
        }

        return redirect()->route('student.page')->with('success', 'Profile updated successfully.');
    }

    // ── ADD TEACHER (admin) ──────────────────────────────────────────
    public function addTeacher(Request $request)
    {
        $validatedData = $request->validate([
            'teacher_id' => 'required|unique:users,user_id',
            'teacher_first_name' => 'required|string|max:255',
            'teacher_middle_name' => 'required|string|max:255',
            'teacher_last_name' => 'required|string|max:255',
            'teacher_email' => 'required|email|unique:teachers,teacher_email',
        ]);

        User::create([
            'user_id' => $validatedData['teacher_id'],
            'role'    => 'teacher',
        ]);

        Teacher::create([
            'user_id'             => $validatedData['teacher_id'],
            'teacher_first_name'  => $validatedData['teacher_first_name'],
            'teacher_middle_name' => $validatedData['teacher_middle_name'],
            'teacher_last_name'   => $validatedData['teacher_last_name'],
            'teacher_email'       => $validatedData['teacher_email'],
            'contact_number'      => '',           // ← required by DB schema
        ]);

        return redirect()->route('admin.page')->with('success', 'Teacher added successfully.');
    }

    // ── ADD STUDENT (admin) ──────────────────────────────────────────
    public function addStudent(Request $request)
    {
        $validatedData = $request->validate([
            'student_id'          => 'required|unique:users,user_id',
            'student_first_name'  => 'required|string|max:255',
            'student_middle_name' => 'nullable|string|max:255',
            'student_last_name'   => 'required|string|max:255',
            'student_email'       => 'required|email|unique:students,student_email',
            'course'              => 'required|string|max:255',
            'section'             => 'required|string|max:255',
            'contact_number'      => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validatedData) {
            User::create([
                'user_id' => $validatedData['student_id'],
                'role'    => 'student',
            ]);

            Student::create([
                'user_id'            => $validatedData['student_id'],
                'student_first_name' => $validatedData['student_first_name'],
                'student_middle_name'=> $validatedData['student_middle_name'] ?? null,
                'student_last_name'  => $validatedData['student_last_name'],
                'student_email'      => $validatedData['student_email'],
                'contact_number'     => $validatedData['contact_number'] ?? '',
                'course'             => $validatedData['course'],
                'section'            => $validatedData['section'],
            ]);
        });

        return redirect()->route('admin.page')->with('success', 'Student added.');
    }

    // ── ADD RUBRIC (admin) ──────────────────────────────────────────
    public function addRubric(Request $request)
    {
        $validatedData = $request->validate([
            'rubric_name'     => 'required|string|max:255',
            'milestone_id'    => 'nullable|exists:milestones,id',
            'criteria_name'   => 'required|array|min:1',
            'criteria_name.*' => 'required|string|max:255',
            'weight'          => 'required|array|min:1',
            'weight.*'        => 'required|numeric|min:0|max:100',
            'score'           => 'required|array|min:1',
            'score.*'         => 'required|numeric|min:0',
        ]);

        if (round(array_sum($validatedData['weight']), 2) != 100) {
            return back()
                ->withErrors(['weight' => 'Criteria weights must add up to 100%.'])
                ->withInput();
        }

        $rubric = Rubric::create([
            'rubric_name'  => $validatedData['rubric_name'],
            'milestone_id' => $validatedData['milestone_id'],
        ]);

        foreach ($validatedData['criteria_name'] as $i => $name) {
            $rubric->criteria()->create([
                'criteria_name' => $name,
                'weight'        => $validatedData['weight'][$i],
                'max_score'     => $validatedData['score'][$i],
            ]);
        }

        return redirect()->route('admin.page')->with('success', 'Rubric created successfully.');
    }

    // ── ADD MILESTONE (admin) ──────────────────────────────────────
    public function addMilestone(Request $request)
    {
        $validatedData = $request->validate([
            'milestone_title' => 'required|string|max:255',
            'capstone_stage'  => 'required|integer|exists:capstone_stages,id',
            'order'           => [
                'required',
                'integer',
                Rule::unique('milestones', 'step_order')
                    ->where('capstone_stage_id', $request->input('capstone_stage'))
            ],
            'description'     => 'nullable|string|max:255',
            'due_date'        => 'required|date',
            'start_date'      => 'required|date',
            
            // Optional Rubric fields
            'add_rubric'      => 'nullable|string',
            'rubric_name'     => 'nullable|required_if:add_rubric,on|string|max:255',
            'criteria_name'   => 'nullable|required_if:add_rubric,on|array',
            'criteria_name.*' => 'nullable|required_if:add_rubric,on|string|max:255',
            'weight'          => 'nullable|required_if:add_rubric,on|array',
            'weight.*'        => 'nullable|required_if:add_rubric,on|numeric|min:0|max:100',
            'score'           => 'nullable|required_if:add_rubric,on|array',
            'score.*'         => 'nullable|required_if:add_rubric,on|numeric|min:0',
        ]);

        if ($request->has('add_rubric') && $request->add_rubric === 'on') {
            if (round(array_sum($validatedData['weight'] ?? []), 2) != 100) {
                return back()
                    ->withErrors(['weight' => 'Criteria weights must add up to 100%.'])
                    ->withInput();
            }
        }

        $milestone = Milestone::create([
            'milestone_title'       => $validatedData['milestone_title'],
            'step_order'            => $validatedData['order'],
            'milestone_description' => $validatedData['description'] ?? '',
            'start_date'            => $validatedData['start_date'],
            'due_date'              => $validatedData['due_date'],
            'capstone_stage_id'     => $validatedData['capstone_stage']
        ]);

        if ($request->has('add_rubric') && $request->add_rubric === 'on') {
            $rubric = Rubric::create([
                'rubric_name'  => $validatedData['rubric_name'] ?: ($validatedData['milestone_title'] . ' Rubric'),
                'milestone_id' => $milestone->id,
            ]);

            if (!empty($validatedData['criteria_name'])) {
                foreach ($validatedData['criteria_name'] as $i => $name) {
                    if ($name) {
                        $rubric->criteria()->create([
                            'criteria_name' => $name,
                            'weight'        => $validatedData['weight'][$i],
                            'max_score'     => $validatedData['score'][$i],
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.page')->with('success', 'Milestone and Rubric added successfully.');
    }

    /**
     * Return the rubric criteria for a given milestone as JSON.
     */
    public function getRubricForMilestone($milestoneId)
    {
        $rubric = Rubric::where('milestone_id', $milestoneId)
                    ->with('criteria')
                    ->first();

        if (!$rubric) {
            return response()->json(['error' => 'No rubric found for this milestone.'], 404);
        }

        return response()->json([
            'rubric_name' => $rubric->rubric_name,
            'criteria'    => $rubric->criteria->map(function ($c) {
                return [
                    'id'            => $c->id,
                    'criteria_name' => $c->criteria_name,
                    'weight'        => $c->weight,
                    'max_score'     => $c->max_score,
                ];
            }),
        ]);
    }

    /**
     * Store a RUBRIC-SCORE evaluation for a group (one record per group per milestone).
     *
     * AUTHORIZATION: Panelist ONLY. A teacher must be assigned as a panelist to the
     * evaluation room this group belongs to. Being the group's adviser is NOT enough
     * here — advisers without a panelist seat cannot submit rubric scores.
     */
    public function submitEvaluation(Request $request)
    {
        $validated = $request->validate([
            'group_id'        => 'required|exists:groups,id',
            'milestone_id'    => 'required|exists:milestones,id',
            'score'           => 'required|numeric|min:0',
            'max_score'       => 'required|numeric|min:0',
            'feedback'        => 'nullable|string',
            'attendance'      => 'required|in:present,absent',
            'absent_students' => 'nullable|array',
            'absent_students.*' => 'exists:students,user_id',
            'feedback1'       => 'nullable|string',
            'rubric_scores'   => 'nullable|array',
        ]);

        $teacher = Teacher::where('user_id', Auth::user()->user_id)->firstOrFail();
        $group = Group::findOrFail($validated['group_id']);

        // Rubric scoring is restricted to panelists assigned to this group's room —
        // adviser status alone does NOT grant access here.
        $assignedRoomIds = $teacher->evaluationRooms()->pluck('evaluation_rooms.id')->toArray();
        if (!in_array($group->room_id, $assignedRoomIds)) {
            return back()->with('error', 'You are not authorized to evaluate this group because it is not in your assigned classrooms.');
        }

        $milestone = Milestone::findOrFail($validated['milestone_id']);

        // Validate group has students
        $firstMember = $group->team_members()->first();
        if (!$firstMember) {
            return back()->with('error', 'Group has no students.');
        }
        $studentId = $firstMember->user_id;

        // Save rubric scores and feedback text in JSON format in the feedback column
        $feedbackPayload = json_encode([
            'feedback_text' => $validated['feedback'] ?? '',
            'rubric_scores' => $request->input('rubric_scores', []),
        ]);

        // ── 1. Save the Evaluation (Score) ──
        Evaluation::updateOrCreate(
            [
                'group_id'     => $validated['group_id'],
                'milestone_id' => $validated['milestone_id'],
                'teacher_id'   => $teacher->id,
            ],
            [
                'student_id'      => $studentId,
                'score'           => $validated['score'],
                'max_score'       => $validated['max_score'],
                'feedback'        => $feedbackPayload,
                'evaluation_date' => now()->toDateString(),
            ]
        );

        // ── 2. Calculate Auto-Remarks based on Milestone Dates ──
        $evaluationDate = Carbon::now();
        $dueDate = Carbon::parse($milestone->due_date);
        $startDate = $milestone->start_date ? Carbon::parse($milestone->start_date) : null;

        $isCompiled = false;
        $deduction = 0;
        $remarksStatus = '';

        if ($startDate && $evaluationDate->lessThan($startDate)) {
            $isCompiled = true;
            $deduction = 0;
            $remarksStatus = 'Early Submission';
        } elseif ($evaluationDate->lessThanOrEqualTo($dueDate)) {
            $isCompiled = true;
            $deduction = 0;
            $remarksStatus = 'On Time Compliance';
        } else {
            $isCompiled = false;
            $daysLate = $evaluationDate->diffInDays($dueDate);
            $deduction = $daysLate * 10; // 10 points per day late
            $remarksStatus = "Late Submission ({$daysLate} day(s) late)";
        }

        // ── 3. Save the Remarks (Attendance & Auto-Compliance) ──
        \App\Models\Remarks::updateOrCreate(
            [
                'group_id'     => $validated['group_id'],
                'milestone_id' => $validated['milestone_id'],
            ],
            [
                'adviser_id'       => (string) $teacher->id,
                'all_present'      => $validated['attendance'] === 'present',
                'compiled'         => $isCompiled,
                'deduction_points' => $deduction,
                'feedback'         => $validated['feedback1'] ?? '',
                'remarks'          => $remarksStatus,
                'date_evaluated'   => now(),
            ]
        );

        // ── 4. Track Absences (if any) ──
        if ($validated['attendance'] === 'absent' && !empty($validated['absent_students'])) {
            \App\Models\Absence::where('group_id', $validated['group_id'])
                ->where('milestone_id', $validated['milestone_id'])
                ->delete();

            foreach ($validated['absent_students'] as $absentUserId) {
                \App\Models\Absence::create([
                    'group_id'     => $validated['group_id'],
                    'milestone_id' => $validated['milestone_id'],
                    'user_id'      => $absentUserId,
                ]);
            }
        }

        // ── 5. Mark the Group's Milestone as Completed ──
        \App\Models\GroupMilestones::updateOrCreate(
            ['group_id' => $validated['group_id'], 'milestone_id' => $validated['milestone_id']],
            ['status' => 'completed', 'completion_date' => now()->toDateString()]
        );
        // ── 6. Auto-issue certificate if this milestone has one ──
        $this->autoIssueCertificateIfEligible($validated['group_id'], $validated['milestone_id']);

        return redirect()->route('teacher.page')->with('success', 'Evaluation saved successfully! System marked it as ' . $remarksStatus);
    }

    /**
     * Store a REMARK evaluation (attendance + auto-compliance) for a group/milestone.
     *
     * AUTHORIZATION: Adviser OR panelist. Unlike submitEvaluation (rubric scoring),
     * a teacher who advises this group can do this even if they are not assigned
     * to any evaluation room / panelist seat for it.
     */
    public function evaluateMilestoneRemark(Request $request)
    {
        $validated = $request->validate([
            'group_id'           => 'required|exists:groups,id',
            'milestone_id'       => 'required|exists:milestones,id',
            'attendance'         => 'required|in:present,absent',
            'absent_students'    => 'nullable|array',
            'absent_students.*'  => 'exists:students,user_id',
            'feedback'           => 'nullable|string',
        ]);

        $teacher = Teacher::where('user_id', Auth::user()->user_id)->firstOrFail();
        $group = Group::findOrFail($validated['group_id']);

        $assignedRoomIds = $teacher->evaluationRooms()->pluck('evaluation_rooms.id')->toArray();
        $isPanelist = in_array($group->room_id, $assignedRoomIds);
        $isAdviser = $group->adviser_id === $teacher->id;

        // Check if the milestone has an associated rubric
        $hasRubric = \App\Models\Rubric::where('milestone_id', $validated['milestone_id'])->exists();

        if ($hasRubric) {
            // ONLY panels can evaluate if there is a rubric
            if (!$isPanelist) {
                return response()->json(['error' => 'Only the panelist of this room is authorized to evaluate this milestone because it has an associated rubric.'], 403);
            }
        } else {
            // BOTH adviser and panelist can evaluate if there is NO rubric
            if (!$isPanelist && !$isAdviser) {
                return response()->json(['error' => 'You are not authorized to evaluate this milestone.'], 403);
            }
        }

        $alreadyCompleted = \App\Models\GroupMilestones::where('group_id', $validated['group_id'])
            ->where('milestone_id', $validated['milestone_id'])
            ->where('status', 'completed')
            ->exists();

        if ($alreadyCompleted) {
            return response()->json(['error' => 'This milestone has already been evaluated.'], 422);
        }

        $milestone = Milestone::findOrFail($validated['milestone_id']);

        $evaluationDate = Carbon::now();
        $dueDate = Carbon::parse($milestone->due_date);
        $startDate = $milestone->start_date ? Carbon::parse($milestone->start_date) : null;

        if ($startDate && $evaluationDate->lessThan($startDate)) {
            // Evaluated before the official start date → Early
            $isCompiled = true;
            $deduction = 0;
            $remarksStatus = 'Early Submission';
        } elseif ($evaluationDate->lessThanOrEqualTo($dueDate)) {
            // On or before due date → On Time
            $isCompiled = true;
            $deduction = 0;
            $remarksStatus = 'On Time Compliance';
        } else {
            // Evaluated after due date → Late
            $isCompiled = false;
            $daysLate = (int) $evaluationDate->diffInDays($dueDate, true); // absolute
            $deduction = $daysLate * 10;
            $remarksStatus = "Late Submission ({$daysLate} day(s) late)";
        }

        $remark = \App\Models\Remarks::create([
            'group_id'         => $validated['group_id'],
            'milestone_id'     => $validated['milestone_id'],
            'adviser_id'       => (string) $teacher->id,
            'all_present'      => $validated['attendance'] === 'present',
            'compiled'         => $isCompiled,
            'deduction_points' => $deduction,
            'feedback'         => $validated['feedback'] ?? '',
            'remarks'          => $remarksStatus,
            'date_evaluated'   => now(),
        ]);

        $absentNames = [];
        if ($validated['attendance'] === 'absent' && !empty($validated['absent_students'])) {
            foreach ($validated['absent_students'] as $absentUserId) {
                \App\Models\Absence::create([
                    'group_id'     => $validated['group_id'],
                    'milestone_id' => $validated['milestone_id'],
                    'user_id'      => $absentUserId,
                ]);
                $s = Student::where('user_id', $absentUserId)->first();
                $absentNames[] = $s ? trim($s->student_first_name.' '.$s->student_last_name) : $absentUserId;
            }
        }

        \App\Models\GroupMilestones::updateOrCreate(
            ['group_id' => $validated['group_id'], 'milestone_id' => $validated['milestone_id']],
            ['status' => 'completed', 'completion_date' => now()->toDateString()]
        );
        // Auto-issue certificate if this milestone has one
        $this->autoIssueCertificateIfEligible($validated['group_id'], $validated['milestone_id']);

        $milestoneCount = Milestone::count();
        $completedCount = \App\Models\GroupMilestones::where('group_id', $validated['group_id'])
            ->where('status', 'completed')->count();
        $overallProgress = $milestoneCount > 0 ? round(($completedCount / $milestoneCount) * 100) : 0;

        return response()->json([
            'success'          => true,
            'overall_progress' => $overallProgress,
            'milestone_id'     => $milestone->id,
            'remarks' => [
                'all_present'      => $remark->all_present,
                'compiled'         => $remark->compiled,
                'deduction_points' => (int) $remark->deduction_points,
                'feedback'         => $remark->feedback,
                'remarks_status'   => $remark->remarks,
            ],
            'absent_students'  => $absentNames,
        ]);
    }

    /**
     * Milestone IDs already evaluated for a group (feeds the disabled dropdown options).
     * AUTHORIZATION: Adviser OR panelist.
     */
    public function getEvaluatedMilestones($groupId)
    {
        $teacher = Teacher::where('user_id', Auth::user()->user_id)->firstOrFail();
        $group = Group::findOrFail($groupId);

        $isAdviser = $group->adviser_id === $teacher->id;
        $assignedRoomIds = $teacher->evaluationRooms()->pluck('evaluation_rooms.id')->toArray();
        $isPanelist = in_array($group->room_id, $assignedRoomIds);

        if (!$isAdviser && !$isPanelist) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(
            Evaluation::where('group_id', $groupId)
                ->where('teacher_id', $teacher->id)
                ->pluck('milestone_id')
        );
    }

    /**
     * Group details (members, section, etc.) for the teacher-side view/edit modals.
     * AUTHORIZATION: Adviser OR panelist.
     */
    public function getGroupDetails($groupId)
    {
        $teacher = Teacher::where('user_id', Auth::user()->user_id)->firstOrFail();
        $group = Group::with('team_members.student.user', 'students')->findOrFail($groupId);

        $isAdviser = $group->adviser_id === $teacher->id;
        $assignedRoomIds = $teacher->evaluationRooms()->pluck('evaluation_rooms.id')->toArray();
        $isPanelist = in_array($group->room_id, $assignedRoomIds);

        $isSectionTeacher = false;
        if ($group->section_id) {
            $isSectionTeacher = \App\Models\Section::where('id', $group->section_id)
                ->where('user_id', $teacher->user_id)
                ->exists();
        }

        if (!$isAdviser && !$isPanelist && !$isSectionTeacher) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'id'             => $group->id,
            'group_name'     => $group->group_name,
            'capstone_title' => $group->capstone_title,
            'section'        => $group->students->first()?->section ?? null,
            'members'        => $group->team_members->map(fn($tm) => [
                'user_id' => $tm->user_id,
                'name'    => trim(
                    (optional($tm->student)->student_first_name ?? '') . ' ' .
                    (optional($tm->student)->student_last_name ?? '')
                ),
                'role'    => $tm->role,
            ]),
        ]);
    }

    /**
     * Edit team members for a group (teacher side).
     * AUTHORIZATION: Adviser OR panelist.
     */
    public function updateGroup(Request $request, $groupId)
    {
        $teacher = Teacher::where('user_id', Auth::user()->user_id)->firstOrFail();
        $group = Group::findOrFail($groupId);

        $isAdviser = $group->adviser_id === $teacher->id;
        $assignedRoomIds = $teacher->evaluationRooms()->pluck('evaluation_rooms.id')->toArray();
        $isPanelist = in_array($group->room_id, $assignedRoomIds);

        if (!$isAdviser && !$isPanelist) {
            return back()->with('error', 'You are not authorized to update this group.');
        }

        $validated = $request->validate([
            'group_name'         => 'required|string|max:255|unique:groups,group_name,' . $group->id,
            'capstone_title'     => 'required|string|max:255',
            'students'           => 'required|array|min:2',
            'students.*.user_id' => 'exists:students,user_id',
            'students.*.role'    => 'required|string|in:programmer,designer,researcher',
        ]);

        foreach ($validated['students'] as $studentData) {
            $inOtherGroup = TeamMember::where('user_id', $studentData['user_id'])
                ->where('group_id', '!=', $group->id)
                ->exists();
            if ($inOtherGroup) {
                $student = Student::where('user_id', $studentData['user_id'])->first();
                return back()
                    ->withErrors(['students' => "Student {$student->student_first_name} {$student->student_last_name} is already in another group."])
                    ->withInput();
            }
        }

        $group->update([
            'group_name'     => $validated['group_name'],
            'capstone_title' => $validated['capstone_title'],
        ]);

        TeamMember::where('group_id', $group->id)->delete();
        foreach ($validated['students'] as $studentData) {
            TeamMember::create([
                'group_id' => $group->id,
                'user_id'  => $studentData['user_id'],
                'role'     => $studentData['role'],
            ]);
        }

        return redirect()->route('teacher.page')->with('success', 'Team updated successfully!');
    }

    // ── UPDATE PASSWORD (shared) ─────────────────────────────────────
    public function updatePassword(Request $request)
    {
        $user = User::find(Auth::id());

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'You must be logged in.']);
        }

        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('teacher.page')->with('success', 'Password updated successfully.');
    }

    // ── STUDENT EDIT (admin) ─────────────────────────────────────────
    public function editStudent(Request $request)
    {
        $validated = $request->validate([
            'original_student_id' => 'required|string|exists:students,user_id',
            'student_id' => 'required|string',
            'student_first_name' => 'required|string',
            'student_middle_name' => 'nullable|string',
            'student_last_name' => 'required|string',
            'student_email' => 'required|email',
            'contact_number' => 'required|string|size:11',
            'section' => 'required|string',
        ]);

        $student = Student::where('user_id', $validated['original_student_id'])->firstOrFail();

        $student->update([
            'student_first_name' => $validated['student_first_name'],
            'student_middle_name' => $validated['student_middle_name'],
            'student_last_name' => $validated['student_last_name'],
            'student_email' => $validated['student_email'],
            'contact_number' => $validated['contact_number'],
            'section' => $validated['section'],
        ]);

        return back()->with('success', 'Student updated successfully.');
    }

    public function deleteStudent(Request $request)
    {
        $validated = $request->validate([
            'student_id'     => 'required|string|exists:students,user_id',
            'admin_password' => 'required|string',
        ]);

        $adminUser = Auth::user();

        if (!Hash::check($validated['admin_password'], $adminUser->password)) {
            return back()
                ->withErrors(['admin_password' => 'Incorrect password. Student was not deleted.'])
                ->withInput();
        }

        DB::transaction(function () use ($validated) {
            TeamMember::where('user_id', $validated['student_id'])->delete();
            Evaluation::where('student_id', $validated['student_id'])->delete();
            Student::where('user_id', $validated['student_id'])->delete();
            User::where('user_id', $validated['student_id'])->delete();
        });

        return back()->with('success', 'Student deleted successfully.');
    }

    // ── TEACHER EDIT (admin) ──────────────────────────────────────
    public function editTeacher(Request $request)
    {
        $validated = $request->validate([
            'original_teacher_id' => 'required|string|exists:teachers,user_id',
            'teacher_id'           => 'required|string',
            'teacher_first_name'   => 'required|string|max:255',
            'teacher_middle_name'  => 'nullable|string|max:255',
            'teacher_last_name'    => 'required|string|max:255',
            'teacher_email'        => 'required|email',
            'contact_number'       => 'nullable|string|size:11',
        ]);

        $teacher = Teacher::where('user_id', $validated['original_teacher_id'])->firstOrFail();

        $validated['teacher_email'] = $request->validate([
            'teacher_email' => 'required|email|unique:teachers,teacher_email,' . $teacher->id,
        ])['teacher_email'];

        $teacher->update([
            'teacher_first_name'  => $validated['teacher_first_name'],
            'teacher_middle_name' => $validated['teacher_middle_name'],
            'teacher_last_name'   => $validated['teacher_last_name'],
            'teacher_email'       => $validated['teacher_email'],
            'contact_number'      => $validated['contact_number'] ?? '',
        ]);

        return back()->with('success', 'Teacher updated successfully.');
    }

    public function deleteTeacher(Request $request)
    {
        $validated = $request->validate([
            'teacher_id'      => 'required|string|exists:teachers,user_id',
            'admin_password'  => 'required|string',
        ]);

        if (!Hash::check($validated['admin_password'], Auth::user()->password)) {
            return back()
                ->withErrors(['admin_password' => 'Incorrect password. Teacher was not deleted.'])
                ->withInput();
        }

        $teacher = Teacher::where('user_id', $validated['teacher_id'])->firstOrFail();

        // Block deletion if they're still advising groups — force reassignment first
        $activeGroups = Group::where('adviser_id', $teacher->id)->count();
        if ($activeGroups > 0) {
            return back()->withErrors([
                'teacher_id' => "Cannot delete: this teacher is still adviser for {$activeGroups} group(s). Reassign those groups first."
            ]);
        }

        DB::transaction(function () use ($teacher, $validated) {
            // Free up any sections assigned to this teacher
            Section::where('user_id', $teacher->user_id)->update(['user_id' => null]);

            $teacher->delete();
            User::where('user_id', $validated['teacher_id'])->delete();
        });

        return back()->with('success', 'Teacher deleted successfully.');
    }

    // ── RUBRIC GET/UPDATE/DELETE (admin) ────────────────────────────
    public function getRubric($id)
    {
        $rubric = Rubric::with('criteria')->findOrFail($id);

        return response()->json([
            'id' => $rubric->id,
            'rubric_name' => $rubric->rubric_name,
            'capstone_id' => $rubric->milestone->capstone_stage_id ?? null,
            'milestone_id' => $rubric->milestone_id,
            'criteria' => $rubric->criteria->map(function ($c) {
                return [
                    'criteria_name' => $c->criteria_name,
                    'weight' => $c->weight,
                    'max_score' => $c->max_score,
                ];
            }),
        ]);
    }

    public function updateRubric(Request $request, $id)
    {
        $validated = $request->validate([
            'rubric_name' => 'required|string|max:255',
            'capstone_id' => 'required|exists:capstone_stages,id',
            'milestone_id' => 'required|exists:milestones,id',
            'criteria_name' => 'required|array|min:1',
            'criteria_name.*' => 'required|string|max:255',
            'weight' => 'required|array|min:1',
            'weight.*' => 'required|numeric|min:0|max:100',
            'score' => 'required|array|min:1',
            'score.*' => 'required|numeric|min:0',
        ]);

        $totalWeight = array_sum($validated['weight']);
        if (round($totalWeight, 2) != 100) {
            return back()->withErrors(['weight' => 'Total weight must equal 100%.'])->withInput();
        }

        $rubric = Rubric::findOrFail($id);
        $rubric->update([
            'rubric_name' => $validated['rubric_name'],
            'milestone_id' => $validated['milestone_id'],
        ]);

        $rubric->criteria()->delete();
        foreach ($validated['criteria_name'] as $i => $name) {
            $rubric->criteria()->create([
                'criteria_name' => $name,
                'weight' => $validated['weight'][$i],
                'max_score' => $validated['score'][$i],
            ]);
        }

        return redirect()->route('admin.page')->with('success', 'Rubric updated successfully.');
    }

    public function deleteRubrics(Request $request)
    {
        $validated = $request->validate([
            'rubric_id' => 'required|exists:rubrics,id',
            'admin_password' => 'required|string',
        ]);

        if (!Hash::check($validated['admin_password'], Auth::user()->password)) {
            return back()->withErrors(['admin_password' => 'Incorrect password. Rubric was not deleted.'])->withInput();
        }

        $rubric = Rubric::findOrFail($validated['rubric_id']);
        $rubric->criteria()->delete();
        $rubric->delete();

        return back()->with('success', 'Rubric deleted successfully.');
    }

    /**
     * Full progress payload for the "View Progress" modal (student or teacher side).
     * AUTHORIZATION (teacher role): Adviser OR panelist. `is_adviser` in the JSON
     * response reflects TRUE adviser status and controls whether the front-end
     * renders the interactive attendance/remark-evaluation controls.
     */
    public function getGroupProgress($groupId)
    {
        $user = Auth::user();
        $group = Group::with(['students', 'groupMilestones.milestone', 'team_members.student.user'])
            ->findOrFail($groupId);

        $isAdviser = false;
        $isPanelist = false;
        if ($user->role === 'teacher') {
            $teacher = Teacher::where('user_id', $user->user_id)->first();
            if (!$teacher) {
                abort(403, 'Teacher profile not found.');
            }

            $isAdviserOfGroup = $group->adviser_id === $teacher->id;
            $assignedRoomIds = $teacher->evaluationRooms()->pluck('evaluation_rooms.id')->toArray();
            $isPanelistOfGroup = in_array($group->room_id, $assignedRoomIds);

            $isSectionTeacher = false;
            if ($group->section_id) {
                $isSectionTeacher = \App\Models\Section::where('id', $group->section_id)
                    ->where('user_id', $teacher->user_id)
                    ->exists();
            }

            if (!$isAdviserOfGroup && !$isPanelistOfGroup && !$isSectionTeacher) {
                abort(403, 'You are not authorized to view the progress of this group.');
            }

            // Only TRUE advisers get the interactive attendance/remark controls in the modal.
            $isAdviser = $isAdviserOfGroup;
            $isPanelist = $isPanelistOfGroup;
        }

        $enabledStageIds = CapstoneStages::where('is_enabled', true)->pluck('id');
        $milestones = Milestone::whereIn('capstone_stage_id', $enabledStageIds)->with(['capstoneStage', 'rubrics'])->orderBy('step_order')->get();
        $enabledMilestoneIds = $milestones->pluck('id')->toArray();
        $completedMilestoneIds = $group->groupMilestones
            ->where('status', 'completed')
            ->whereIn('milestone_id', $enabledMilestoneIds)
            ->pluck('milestone_id')
            ->toArray();

        $overallProgress = $milestones->count() ? round((count($completedMilestoneIds) / $milestones->count()) * 100) : 0;
        $nextMilestone = $milestones->first(fn($m) => !in_array($m->id, $completedMilestoneIds));

        $evaluations = Evaluation::where('group_id', $groupId)
            ->with(['milestone', 'teacher.user'])
            ->latest('evaluation_date')
            ->limit(5)
            ->get()
            ->map(function ($e) {
                $decoded = json_decode($e->feedback, true);
                if (is_array($decoded) && isset($decoded['feedback_text'])) {
                    $feedbackText = $decoded['feedback_text'];
                    $rubricScores = $decoded['rubric_scores'] ?? [];
                } else {
                    $feedbackText = $e->feedback;
                    $rubricScores = [];
                }

                $rubric = Rubric::where('milestone_id', $e->milestone_id)->with('criteria')->first();
                $criteriaData = [];
                if ($rubric) {
                    foreach ($rubric->criteria as $criterion) {
                        $criteriaData[] = [
                            'criteria_name' => $criterion->criteria_name,
                            'weight'        => $criterion->weight,
                            'max_score'     => $criterion->max_score,
                            'given_score'   => $rubricScores[$criterion->id] ?? 0,
                        ];
                    }
                }

                return [
                    'milestone_id'    => $e->milestone_id,
                    'milestone_title' => $e->milestone->milestone_title ?? 'Evaluation',
                    'teacher_name'    => $e->teacher->user->name ?? 'Teacher',
                    'evaluation_date' => $e->evaluation_date,
                    'score'           => $e->score,
                    'max_score'       => $e->max_score,
                    'feedback'        => $feedbackText,
                    'criteria'        => $criteriaData,
                ];
            });

        // ── Remarks & Absences for this group (feeds the Remarks block per milestone) ──
        $remarksByMilestone = \App\Models\Remarks::where('group_id', $groupId)
            ->get()
            ->keyBy('milestone_id');

        $allAbsences = \App\Models\Absence::where('group_id', $groupId)->get();
        $absentStudentIds = $allAbsences->pluck('user_id')->unique();
        $absentStudentsById = Student::whereIn('user_id', $absentStudentIds)->get()->keyBy('user_id');

        $groupMilestonesMap = $group->groupMilestones->keyBy('milestone_id');

        $milestoneData = $milestones->map(function ($m) use (
            $completedMilestoneIds, $nextMilestone, $remarksByMilestone,
            $allAbsences, $absentStudentsById, $groupMilestonesMap
        ) {
            $isCompleted = in_array($m->id, $completedMilestoneIds);
            $isNext = $m->id === $nextMilestone?->id && !$isCompleted;

            // Get the real completion date from the pivot table
            $gm = $groupMilestonesMap->get($m->id);
            $completionDate = $gm ? $gm->completion_date : null;

            $r = $remarksByMilestone->get($m->id);
            $absentNames = $allAbsences->where('milestone_id', $m->id)->map(function ($a) use ($absentStudentsById) {
                $s = $absentStudentsById->get($a->user_id);
                return $s ? trim($s->student_first_name . ' ' . $s->student_last_name) : $a->user_id;
            })->values();

            return [
                'id'                => $m->id,
                'title'             => $m->milestone_title,
                'description'       => $m->milestone_description,
                'start_date'        => $m->start_date,
                'due_date'          => $m->due_date,
                'step_order'        => $m->step_order,
                'is_completed'         => $isCompleted,
                'is_next'              => $isNext,
                'completion_date'      => $completionDate,
                'capstone_stage_id'    => $m->capstone_stage_id,
                'capstone_stage_title' => $m->capstoneStage->stage_title ?? 'Capstone',
                'has_rubric'           => $m->rubrics->isNotEmpty(),
                'remarks'              => $r ? [
                    'all_present'      => (bool) $r->all_present,
                    'compiled'         => (bool) $r->compiled,
                    'deduction_points' => (int) $r->deduction_points,
                    'feedback'         => $r->feedback,
                    'remarks_status'   => $r->remarks,
                    'date_evaluated'   => $r->date_evaluated,
                ] : null,
                'absent_students'   => $absentNames,
            ];
        });

        return response()->json([
            'group_name'        => $group->group_name,
            'overall_progress'  => $overallProgress,
            'milestones'        => $milestoneData,
            'next_milestone'    => $nextMilestone ? [
                'title'       => $nextMilestone->milestone_title,
                'description' => $nextMilestone->milestone_description,
                'start_date'  => $nextMilestone->start_date,
                'due_date'    => $nextMilestone->due_date,
            ] : null,
            'evaluations'       => $evaluations,
            'is_adviser'        => $isAdviser,
            'is_panelist'       => $isPanelist,
        ]);
    }

    // ── STUDENT IMPORT ──────────────────────────────────────────
    public function importStudents(Request $request)
    {
        $request->validate([
            'file' => 'required|file|extensions:xlsx,csv,xls',
        ]);

        try {
            $import = new StudentsImport;
            Excel::import($import, $request->file('file'));

            if ($import->failures()->isNotEmpty()) {
                $messages = $import->failures()->map(function ($failure) {
                    return "Row {$failure->row()}: " . implode(', ', $failure->errors());
                });
                return back()->withErrors($messages)
                    ->with('import_students', true)
                    ->with('error', 'Student import was unsuccessful.');
            }

            return back()->with('success', 'Students imported successfully.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Student Import Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()
                ->withErrors(['import_error' => 'Import Error: ' . $e->getMessage()])
                ->with('import_students', true)
                ->with('error', 'Student import failed. ' . $e->getMessage());
        }
    }

    public function downloadStudentTemplate()
    {
        $headers = ['student_id', 'student_first_name', 'student_middle_name', 'student_last_name', 'student_email', 'contact_number', 'course', 'section'];

        return response()->streamDownload(function () use ($headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            fclose($handle);
        }, 'student_import_template.csv');
    }

    // ── TEACHER IMPORT ───────────────────────────────────────────
    public function importTeachers(Request $request)
    {
        $request->validate([
            'file' => 'required|file|extensions:xlsx,csv,xls',
        ]);

        try {
            $import = new TeachersImport;
            Excel::import($import, $request->file('file'));

            if ($import->failures()->isNotEmpty()) {
                $messages = $import->failures()->map(function ($failure) {
                    return "Row {$failure->row()}: " . implode(', ', $failure->errors());
                });
                return back()->withErrors($messages)
                    ->with('import_teachers', true)
                    ->with('error', 'Teacher import was unsuccessful.');
            }

            return back()->with('success', 'Teachers imported successfully.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Teacher Import Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()
                ->withErrors(['import_error' => 'Import Error: ' . $e->getMessage()])
                ->with('import_teachers', true)
                ->with('error', 'Teacher import failed. ' . $e->getMessage());
        }
    }

    public function downloadTeacherTemplate()
    {
        $headers = ['teacher_id', 'teacher_first_name', 'teacher_middle_name', 'teacher_last_name', 'teacher_email', 'contact_number'];

        return response()->streamDownload(function () use ($headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            fclose($handle);
        }, 'teacher_import_template.csv');
    }

    // ── GROUP GET/UPDATE (admin) ─────────────────────────────────
    public function getGroupAdmin($id)
    {
        $group = Group::with(['adviser', 'section', 'team_members.student.user'])->findOrFail($id);

        return response()->json([
            'id'             => $group->id,
            'group_name'     => $group->group_name,
            'capstone_title' => $group->capstone_title,
            'adviser_id'     => $group->adviser_id,
            'section_id'     => $group->section_id,
            'section_name'   => $group->section->section_name ?? null,
            'members'        => $group->team_members->map(fn($tm) => [
                'user_id' => $tm->user_id,
                'name'    => trim(
                    (optional($tm->student)->student_first_name ?? '') . ' ' .
                    (optional($tm->student)->student_last_name ?? '')
                ),
                'role'    => $tm->role,
            ]),
        ]);
    }

    public function updateGroupAdmin(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $validated = $request->validate([
            'group_name'          => 'required|string|max:255|unique:groups,group_name,' . $group->id,
            'capstone_title'      => 'required|string|max:255',
            'adviser_id'          => 'required|exists:teachers,id',
            'students'            => 'required|array|min:1',
            'students.*.user_id'  => 'exists:students,user_id',
            'students.*.role'     => 'required|string|in:programmer,designer,researcher',
        ]);

        foreach ($validated['students'] as $studentData) {
            $inOtherGroup = TeamMember::where('user_id', $studentData['user_id'])
                ->where('group_id', '!=', $group->id)
                ->exists();
            if ($inOtherGroup) {
                $student = Student::where('user_id', $studentData['user_id'])->first();
                return response()->json([
                    'errors' => ['students' => ["Student {$student->student_first_name} {$student->student_last_name} is already in another group."]]
                ], 422);
            }
        }

        $group->update([
            'group_name'     => $validated['group_name'],
            'capstone_title' => $validated['capstone_title'],
            'adviser_id'     => $validated['adviser_id'],
        ]);

        TeamMember::where('group_id', $group->id)->delete();
        foreach ($validated['students'] as $studentData) {
            TeamMember::create([
                'group_id' => $group->id,
                'user_id'  => $studentData['user_id'],
                'role'     => $studentData['role'],
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Create N evaluation rooms, distribute selected panelists round-robin across
     * them (each panelist ends up in exactly ONE room — any prior room membership
     * is detached first), then divide all groups evenly among the new rooms.
     */
   /**
 * Regenerate a room's join code (admin only).
 */
public function regenerateRoomCode($roomId)
{
    $room = EvaluationRoom::findOrFail($roomId);
    $room->join_code = EvaluationRoom::generateUniqueCode();
    $room->save();

    return response()->json(['success' => true, 'join_code' => $room->join_code]);
}

/**
 * Teacher enters a room's join code to become its panelist.
 * Allows multiple panelists per room and multiple rooms per teacher.
 */
public function joinRoomWithCode(Request $request)
{
                
     Log::info('Join room request', ['code' => $request->join_code]);

    $teacher = Teacher::where('user_id', Auth::user()->user_id)->first();
    Log::info('Teacher', ['teacher' => $teacher]);

    $room = EvaluationRoom::where('join_code', strtoupper(trim($request->join_code)))->first();
    Log::info('Room', ['room' => $room]);

    $validated = $request->validate([
        'join_code' => 'required|string',
    ]);

    $teacher = Teacher::where('user_id', Auth::user()->user_id)->first();
    if (!$teacher) {
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json(['error' => 'Teacher profile not found.'], 404);
        }
        return redirect()->back()->with('error', 'Teacher profile not found.');
    }

    $room = EvaluationRoom::where('join_code', strtoupper(trim($validated['join_code'])))->first();
    if (!$room) {
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json(['error' => 'Invalid code. Please check with the admin and try again.'], 404);
        }
        return redirect()->back()->with('error', 'Invalid code. Please check with the admin and try again.');
    }

    $alreadyThisTeacher = $room->panelists()->where('teacher_id', $teacher->id)->exists();
    if ($alreadyThisTeacher) {
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'room_name' => $room->room_name, 'already_joined' => true]);
        }
        return redirect()->back()->with('error', "Already joined {$room->room_name}.");
    }

    // Check if teacher is already assigned to ANY room
    $isAlreadyAssigned = DB::table('room_panelists')->where('teacher_id', $teacher->id)->exists();
    if ($isAlreadyAssigned) {
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json(['error' => 'You are already assigned to an evaluation room.'], 422);
        }
        return redirect()->back()->with('error', 'You are already assigned to an evaluation room.');
    }

    // Add teacher as panelist to this room (avoiding duplicate entries)
    $room->panelists()->attach($teacher->id);

    if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
        return response()->json(['success' => true, 'room_name' => $room->room_name]);
    }

    return redirect()->back()->with('success', "Joined {$room->room_name} successfully!");
}

    /**
     * Add a single panelist to a room manually.
     */
    public function addPanelist(Request $request, $roomId)
{
    $validated = $request->validate([
        'teacher_id' => 'required|exists:teachers,id',
    ]);

    $room = EvaluationRoom::findOrFail($roomId);
    $teacher = Teacher::findOrFail($validated['teacher_id']);

    $alreadyThisTeacher = $room->panelists()->where('teacher_id', $teacher->id)->exists();
    if (!$alreadyThisTeacher) {
        // Check if teacher is already assigned to ANY room
        $isAlreadyAssigned = DB::table('room_panelists')->where('teacher_id', $teacher->id)->exists();
        if ($isAlreadyAssigned) {
            return response()->json(['error' => 'This teacher is already assigned to an evaluation room.'], 422);
        }

        // Add panelist to this room (avoiding duplicate entries)
        $room->panelists()->attach($teacher->id);
    }
                
    return response()->json([
        'success' => true,
        'panelist' => [
            'id'   => $teacher->id,
            'name' => $teacher->teacher_first_name . ' ' . $teacher->teacher_last_name,
        ],
    ]);
}

    public function removePanelist($roomId, $teacherId)
    {
        EvaluationRoom::findOrFail($roomId)->panelists()->detach($teacherId);
        return response()->json(['success' => true]);
    }

    public function deleteRoom(Request $request)
    {
        $validated = $request->validate([
            'room_id'        => 'required|integer|exists:evaluation_rooms,id',
            'admin_password' => 'required|string',
        ]);

        $adminUser = Auth::user();

        if (!Hash::check($validated['admin_password'], $adminUser->password)) {
            return back()
                ->withErrors(['admin_password' => 'Incorrect password. Evaluation room was not deleted.'])
                ->withInput();
        }

        EvaluationRoom::findOrFail($validated['room_id'])->delete();

        return back()->with('success', 'Evaluation room deleted successfully.');
    }
    public function getMilestone($id)
    {
        $milestone = Milestone::findOrFail($id);
        $certificate = Certificate::where('milestone_id', $milestone->id)->first();
        $rubric = Rubric::where('milestone_id', $milestone->id)->with('criteria')->first();

        return response()->json([
            'id'                     => $milestone->id,
            'milestone_title'        => $milestone->milestone_title,
            'milestone_description'  => $milestone->milestone_description,
            'capstone_stage_id'      => $milestone->capstone_stage_id,
            'step_order'             => $milestone->step_order,
            'start_date'             => $milestone->start_date,
            'due_date'               => $milestone->due_date,
            'certificate'            => $certificate ? [
                'id'                       => $certificate->id,
                'certificate_title'        => $certificate->certificate_title,
                'certificate_description'  => $certificate->certificate_description,
            ] : null,
            'rubric'                 => $rubric ? [
                'id'            => $rubric->id,
                'rubric_name'   => $rubric->rubric_name,
                'criteria'      => $rubric->criteria->map(fn($c) => [
                    'id'            => $c->id,
                    'criteria_name' => $c->criteria_name,
                    'weight'        => $c->weight,
                    'max_score'     => $c->max_score,
                ])
            ] : null,
        ]);
    }

public function updateMilestone(Request $request, $id)
{
    $milestone = Milestone::findOrFail($id);

    $validated = $request->validate([
        'milestone_title'          => 'required|string|max:255',
        'capstone_stage'           => 'required|integer|exists:capstone_stages,id',
        'order'                    => [
            'required',
            'integer',
            Rule::unique('milestones', 'step_order')
                ->ignore($milestone->id)
                ->where('capstone_stage_id', $request->input('capstone_stage'))
        ],
        'description'              => 'required|string|max:255',
        'start_date'               => 'required|date',
        'due_date'                 => 'required|date|after_or_equal:start_date',
        'has_certificate'          => 'nullable|boolean',
        'certificate_title'        => 'required_if:has_certificate,1|nullable|string|max:255',
        'certificate_description'  => 'required_if:has_certificate,1|nullable|string',

        // Optional Rubric fields
        'add_rubric'               => 'nullable|string',
        'rubric_name'              => 'nullable|required_if:add_rubric,on|string|max:255',
        'criteria_name'            => 'nullable|required_if:add_rubric,on|array',
        'criteria_name.*'          => 'nullable|required_if:add_rubric,on|string|max:255',
        'weight'                   => 'nullable|required_if:add_rubric,on|array',
        'weight.*'                 => 'nullable|required_if:add_rubric,on|numeric|min:0|max:100',
        'score'                    => 'nullable|required_if:add_rubric,on|array',
        'score.*'                  => 'nullable|required_if:add_rubric,on|numeric|min:0',
    ]);

    $milestone->update([
        'milestone_title'        => $validated['milestone_title'],
        'capstone_stage_id'      => $validated['capstone_stage'],
        'step_order'             => $validated['order'],
        'milestone_description'  => $validated['description'],
        'start_date'             => $validated['start_date'],
        'due_date'               => $validated['due_date'],
    ]);

    if (!empty($validated['has_certificate'])) {
        // One certificate per milestone — create it if missing, otherwise update in place
        Certificate::updateOrCreate(
            ['milestone_id' => $milestone->id],
            [
                'certificate_title'       => $validated['certificate_title'],
                'certificate_description' => $validated['certificate_description'],
                'is_locked'               => 1,
            ]
        );
    } else {
        // Admin unchecked "award a certificate" — remove any certificate tied to this milestone
        Certificate::where('milestone_id', $milestone->id)->delete();
    }

    if ($request->has('add_rubric') && $request->add_rubric === 'on') {
        if (round(array_sum($request->input('weight') ?? []), 2) != 100) {
            return response()->json([
                'errors' => ['weight' => ['Criteria weights must add up to 100%.']]
            ], 422);
        }

        // Update or Create Rubric
        $rubric = Rubric::updateOrCreate(
            ['milestone_id' => $milestone->id],
            ['rubric_name'  => $request->input('rubric_name') ?: ($milestone->milestone_title . ' Rubric')]
        );

        // Clear existing criteria and recreate
        $rubric->criteria()->delete();

        if (!empty($request->input('criteria_name'))) {
            foreach ($request->input('criteria_name') as $i => $name) {
                if ($name) {
                    $rubric->criteria()->create([
                        'criteria_name' => $name,
                        'weight'        => $request->input('weight')[$i],
                        'max_score'     => $request->input('score')[$i],
                    ]);
                }
            }
        }
    } else {
        // If "add_rubric" is unchecked, let's delete the rubric and its criteria if they exist
        $existingRubric = Rubric::where('milestone_id', $milestone->id)->first();
        if ($existingRubric) {
            $existingRubric->criteria()->delete();
            $existingRubric->delete();
        }
    }

    return redirect()->route('admin.page')->with('success', 'Milestone updated successfully.');
}
/**
 * Full printable certificate document for a group's earned certificate.
 * Shows capstone title and every team member's name.
 *
 * AUTHORIZATION: admin, the group's adviser, any panelist assigned to the
 * group's room, or a student who is a member of the group.
 */
public function showCertificate($groupId, $certificateId)
{
    $user = Auth::user();
    $group = Group::with(['team_members.student', 'adviser'])->findOrFail($groupId);

    $groupCertificate = GroupCertificate::where('group_id', $groupId)
        ->where('certificate_id', $certificateId)
        ->firstOrFail();

    $certificate = Certificate::findOrFail($certificateId);
    $milestone = Milestone::findOrFail($certificate->milestone_id);

    // ── Authorization ──
    $authorized = false;
    if ($user->role === 'admin') {
        $authorized = true;
    } elseif ($user->role === 'teacher') {
        $teacher = Teacher::where('user_id', $user->user_id)->first();
        if ($teacher) {
            $isAdviser = $group->adviser_id === $teacher->id;
            $assignedRoomIds = $teacher->evaluationRooms()->pluck('evaluation_rooms.id')->toArray();
            $isPanelist = in_array($group->room_id, $assignedRoomIds);
            $authorized = $isAdviser || $isPanelist;
        }
    } elseif ($user->role === 'student') {
        $authorized = $group->team_members->contains('user_id', $user->user_id);
    }

    if (!$authorized) {
        abort(403, 'You are not authorized to view this certificate.');
    }

    $members = $group->team_members->map(function ($tm) {
        return trim(
            (optional($tm->student)->student_first_name ?? '') . ' ' .
            (optional($tm->student)->student_last_name ?? '')
        );
    })->filter()->values()->all();

    $adviserName = $group->adviser
        ? trim($group->adviser->teacher_first_name . ' ' . $group->adviser->teacher_last_name)
        : null;

    return view('certificates.print', [
        'certificate'  => $certificate,
        'group'        => $group,
        'milestone'    => $milestone,
        'members'      => $members,
        'adviserName'  => $adviserName,
        'issuedDate'   => $groupCertificate->issued_date,
    ]);
}

        /**
         * List certificates a group has earned (feeds the "Certificates" section
         * in the teacher/admin group progress modal).
         * AUTHORIZATION: same rule as showCertificate.
         */
        public function getGroupCertificates($groupId)
        {
            $user = Auth::user();
            $group = Group::findOrFail($groupId);

            $authorized = false;
            if ($user->role === 'admin') {
                $authorized = true;
            } elseif ($user->role === 'teacher') {
                $teacher = Teacher::where('user_id', $user->user_id)->first();
                if ($teacher) {
                    $isAdviser = $group->adviser_id === $teacher->id;
                    $assignedRoomIds = $teacher->evaluationRooms()->pluck('evaluation_rooms.id')->toArray();
                    $isPanelist = in_array($group->room_id, $assignedRoomIds);
                    $authorized = $isAdviser || $isPanelist;
                }
            } elseif ($user->role === 'student') {
                $authorized = Student::where('user_id', $user->user_id)
                    ->whereHas('groups', fn($q) => $q->where('groups.id', $groupId))
                    ->exists();
            }

            if (!$authorized) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $earned = GroupCertificate::where('group_id', $groupId)
                ->with('certificate')
                ->get()
                ->map(fn($gc) => [
                    'certificate_id'    => $gc->certificate_id,
                    'certificate_title' => $gc->certificate->certificate_title ?? 'Certificate',
                    'issued_date'       => $gc->issued_date,
                ]);

            return response()->json($earned);
        }
        public function createRoom(Request $request)
{
    $validated = $request->validate([
        'room_count'            => 'required|integer|min:1',
        'required_milestone_id' => 'required|exists:milestones,id',
        'activity_name'         => 'required|string|max:255',
        'panelists'             => 'nullable|array',
        'panelists.*'           => 'exists:teachers,id',
    ]);

    $panelists = $validated['panelists'] ?? [];
    $roomCount = $validated['room_count'];

    foreach ($panelists as $teacherId) {
        $alreadyAssigned = DB::table('room_panelists')->where('teacher_id', $teacherId)->exists();
        if ($alreadyAssigned) {
            $teacherObj = Teacher::find($teacherId);
            $teacherName = $teacherObj ? ($teacherObj->teacher_first_name . ' ' . $teacherObj->teacher_last_name) : 'Selected Teacher';
            return back()->withErrors(['panelists' => "Teacher {$teacherName} is already assigned to another evaluation room."])->withInput();
        }
    }

    DB::transaction(function () use ($validated, $panelists, $roomCount) {
        $requiredMilestoneId = $validated['required_milestone_id'];

        // Get all groups without a room that have completed the required milestone
        $groups = Group::whereNull('room_id')
            ->whereHas('groupMilestones', function ($query) use ($requiredMilestoneId) {
                $query->where('milestone_id', $requiredMilestoneId)
                      ->where('status', 'completed');
            })->get();

        // Divide groups evenly
        $groupsPerRoom = ceil($groups->count() / $roomCount);
        $rooms = [];

        for ($i = 1; $i <= $roomCount; $i++) {
            $baseName = 'Room ' . $i . ' - ' . now()->format('Y-m-d');
            $roomName = $baseName;
            $counter = 1;
            while (EvaluationRoom::where('room_name', $roomName)->exists()) {
                $roomName = $baseName . ' (' . $counter . ')';
                $counter++;
            }

            $room = EvaluationRoom::create([
                'room_name'             => $roomName,
                'join_code'             => EvaluationRoom::generateUniqueCode(),
                'required_milestone_id' => $requiredMilestoneId,
                'activity_name'         => $validated['activity_name'],
            ]);
            $rooms[] = $room;
        }

        // Distribute groups round-robin (only if any groups were found)
        if ($groups->isNotEmpty()) {
            $groups->each(function ($group, $index) use ($rooms) {
                $room = $rooms[$index % count($rooms)];
                $group->room_id = $room->id;
                $group->save();
            });
        }

        // Distribute panelists round-robin (allowing multiple panelists per room, but each in exactly 1 room)
        foreach ($panelists as $index => $teacherId) {
            $room = $rooms[$index % count($rooms)];
            $room->panelists()->attach($teacherId);
        }
    });

    return back()->with('success', 'Evaluation rooms created successfully.');
}
       public function getRoom($roomId)
{
    $room = EvaluationRoom::with(['panelists', 'groups', 'requiredMilestone'])->findOrFail($roomId);
    
    // Get all assigned teacher IDs across all rooms
    $assignedTeacherIds = DB::table('room_panelists')->pluck('teacher_id')->toArray();
    
    // Available teachers are those not assigned to any room
    $availableTeachers = Teacher::whereNotIn('id', $assignedTeacherIds)->get();

    return response()->json([
        'id'                 => $room->id,
        'room_name'          => $room->room_name,
        'join_code'          => $room->join_code,
        'required_milestone' => $room->requiredMilestone ? $room->requiredMilestone->milestone_title : 'None',
        'activity_name'      => $room->activity_name ?? 'N/A',
        'panelists'          => $room->panelists->map(fn($p) => [
            'id'   => $p->id,
            'name' => $p->teacher_first_name . ' ' . $p->teacher_last_name,
        ]),
        'available_teachers' => $availableTeachers->map(fn($t) => [
            'id'   => $t->id,
            'name' => $t->teacher_first_name . ' ' . $t->teacher_last_name,
        ]),
    ]);
}

    // ── SHOW VERIFY EMAIL FORM ───────────────────────
    public function showVerifyEmailForm()
    {
        if (Auth::user()->email_verified_at !== null) {
            return $this->redirectByRole();
        }
        return view('verify-email');
    }

    // ── SEND CODE AFTER LOGIN ────────────────────────
    public function sendVerificationCodeAfterLogin(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
        ], [
            'email.unique' => 'This email is already taken.',
        ]);

        $email = $request->email;

        $code = (string) random_int(100000, 999999);
        Cache::put('verify_code_' . $user->user_id, $code, now()->addMinutes(10));

        $sent = Mailer::send(
            $email,
            $user->user_id,
            'Your Capstone Tracker verification code',
            "<p>Your verification code is:</p><h2>{$code}</h2><p>This code expires in 10 minutes.</p>"
        );

        if (!$sent) {
            return back()->withErrors(['email' => 'Failed to send verification email. Please try again.'])->withInput();
        }

        return back()->with('success', 'A verification code has been sent to your email.')
                      ->with('code_sent', true)
                      ->with('verified_email', $email);
    }

    // ── CONFIRM VERIFICATION CODE ────────────────────
    public function confirmVerificationCode(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'code'  => 'required|string',
        ], [
            'email.unique' => 'This email is already taken.',
        ]);

        $cachedCode = Cache::get('verify_code_' . $user->user_id);

        if (!$cachedCode || $cachedCode !== $request->code) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.'])
                         ->withInput()
                         ->with('code_sent', true)
                         ->with('verified_email', $request->email);
        }

        // ── PERSIST THE VERIFIED EMAIL ──
        $user->email = $request->email;
        $user->email_verified_at = now();
        $user->save();

        // ── UPDATE SYNCED PROFILE EMAIL ──
        switch ($user->role) {
            case 'student':
                Student::where('user_id', $user->user_id)->update(['student_email' => $request->email]);
                break;
            case 'teacher':
                Teacher::where('user_id', $user->user_id)->update(['teacher_email' => $request->email]);
                break;
            case 'admin':
                Admin::where('user_id', $user->user_id)->update(['admin_email' => $request->email]);
                break;
        }

        Cache::forget('verify_code_' . $user->user_id);

        return $this->redirectByRole();
    }

    // ── SHOW FORGOT PASSWORD FORM ───────────────────
    public function showForgotPasswordForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('forgot-password');
    }

    // ── SEND FORGOT PASSWORD CODE ────────────────────
    public function sendForgotPasswordCode(Request $request)
    {
        $request->validate(['user_id' => 'required|string']);

        $user = User::where('user_id', $request->user_id)->first();
        if (!$user) {
            return back()->withErrors(['user_id' => 'This User ID is not registered in our system.'])->withInput();
        }

        // Send to their verified email address on file
        $email = $user->email;
        if (!$email) {
            // If they didn't verify an email yet, look up their profile email on file as a fallback
            $email = match ($user->role) {
                'student' => Student::where('user_id', $user->user_id)->value('student_email'),
                'teacher' => Teacher::where('user_id', $user->user_id)->value('teacher_email'),
                'admin'   => Admin::where('user_id', $user->user_id)->value('admin_email'),
                default   => null,
            };
        }

        if (!$email) {
            return back()->withErrors(['user_id' => 'We could not find an email address associated with this account. Please contact your administrator.'])->withInput();
        }

        $code = (string) random_int(100000, 999999);
        Cache::put('reset_code_' . $user->user_id, $code, now()->addMinutes(10));

        $sent = Mailer::send(
            $email,
            $user->user_id,
            'Your Capstone Tracker password reset code',
            "<p>Your password reset verification code is:</p><h2>{$code}</h2><p>This code expires in 10 minutes.</p>"
        );

        if (!$sent) {
            return back()->withErrors(['user_id' => 'Failed to send password reset code. Please try again.'])->withInput();
        }

        // Mask email for privacy (e.g., j***e@domain.com)
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1] ?? '';
        $maskedName = strlen($name) > 2 ? $name[0] . str_repeat('*', strlen($name) - 2) . $name[strlen($name) - 1] : $name;
        $maskedEmail = $maskedName . '@' . $domain;

        return back()->with('success', "A password reset code has been sent to your email on file ({$maskedEmail}).")
                      ->with('reset_code_sent', true)
                      ->with('reset_user_id', $user->user_id);
    }

    // ── RESET PASSWORD WITH CODE ────────────────────
    public function resetPasswordWithCode(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|string',
            'code'     => 'required|string',
            'password' => 'required|min:6',
        ]);

        $user = User::where('user_id', $request->user_id)->first();
        if (!$user) {
            return back()->withErrors(['user_id' => 'User ID not found.'])
                         ->withInput()
                         ->with('reset_code_sent', true)
                         ->with('reset_user_id', $request->user_id);
        }

        $cachedCode = Cache::get('reset_code_' . $user->user_id);

        if (!$cachedCode || $cachedCode !== $request->code) {
            return back()->withErrors(['code' => 'Invalid or expired reset code.'])
                         ->withInput()
                         ->with('reset_code_sent', true)
                         ->with('reset_user_id', $request->user_id);
        }

        // Update the password
        $user->password = bcrypt($request->password);
        $user->save();

        Cache::forget('reset_code_' . $user->user_id);

        return redirect('/')->with('success', 'Your password has been reset successfully! Please log in.');
    }

    // ── TOGGLE CAPSTONE STAGE ───────────────────────────────────────
    public function toggleCapstoneStage(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'stage_id' => 'required|exists:capstone_stages,id',
        ]);

        $stage = CapstoneStages::findOrFail($request->stage_id);
        
        if (!$stage->is_enabled) {
            // Disable all other active (non-archived) capstone stages
            CapstoneStages::where('is_archived', false)->update(['is_enabled' => 0]);
            $stage->is_enabled = 1;
        } else {
            $stage->is_enabled = 0;
        }
        $stage->save();

        $status = $stage->is_enabled ? 'enabled' : 'disabled';
        return back()->with('success', "{$stage->stage_title} has been {$status} successfully.");
    }

    // ── ARCHIVE CAPSTONE BY YEAR ────────────────────────────────────
    public function archiveCapstoneByYear(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'stage_id'  => 'required',
            'year'      => 'required|integer|min:2000|max:2099',
            'new_title' => 'nullable|string|max:255',
        ]);

        $year = $request->year;
        $stageId = $request->stage_id;

        if ($stageId === 'all') {
            // Find all active capstone stages
            $activeStages = CapstoneStages::where('is_archived', false)->get();
            $totalCount = 0;
            
            foreach ($activeStages as $stage) {
                // Find and archive active groups pointing to this stage
                $groupsToArchive = Group::where('capstone_stage_id', $stage->id)->where('is_archived', false)->get();
                foreach ($groupsToArchive as $g) {
                    $g->is_archived = true;
                    $g->archived_year = $year;
                    $g->save();

                    // Check adviser of this archived group
                    $adviserId = $g->adviser_id;
                    $hasActiveGroups = Group::where('adviser_id', $adviserId)->where('is_archived', false)->exists();
                    if (!$hasActiveGroups) {
                        Teacher::where('id', $adviserId)->update(['is_archived' => true]);
                    }

                    // Check section of this archived group
                    $secId = $g->section_id;
                    $hasActiveGroupsSec = Group::where('section_id', $secId)->where('is_archived', false)->exists();
                    if (!$hasActiveGroupsSec) {
                        Section::where('id', $secId)->update(['is_archived' => true]);
                    }
                }

                // Archive all students in these groups
                $archivedGroupIds = $groupsToArchive->pluck('id')->toArray();
                $studentUserIds = TeamMember::whereIn('group_id', $archivedGroupIds)->pluck('user_id')->toArray();
                Student::whereIn('user_id', $studentUserIds)->update(['is_archived' => true]);

                $totalCount += $groupsToArchive->count();

                // Mark the stage as archived
                $stage->is_archived = true;
                $stage->archived_year = $year;
                $stage->is_enabled = false;
                $stage->save();

                // Create new active cycle stage and clone milestones
                $clonedTitle = "Capstone {$stage->stage_type} - Cycle {$year}";
                $newStage = CapstoneStages::create([
                    'stage_title'   => $clonedTitle,
                    'is_enabled'    => $stage->id == 1 ? true : false, // Enable stage 1 as default
                    'is_archived'   => false,
                    'stage_type'    => $stage->stage_type,
                ]);

                // Duplicate milestones
                $oldMilestones = Milestone::where('capstone_stage_id', $stage->id)->get();

                // Delete active classrooms associated with the archived milestones of this stage
                $oldMilestoneIds = $oldMilestones->pluck('id')->toArray();
                EvaluationRoom::whereIn('required_milestone_id', $oldMilestoneIds)->delete();

                foreach ($oldMilestones as $om) {
                    Milestone::create([
                        'milestone_title'       => $om->milestone_title,
                        'milestone_description' => $om->milestone_description,
                        'capstone_stage_id'     => $newStage->id,
                        'start_date'            => $om->start_date,
                        'due_date'              => $om->due_date,
                        'step_order'            => $om->step_order,
                    ]);
                }
            }

            return back()->with('success', "Successfully archived all active Capstone stages and {$totalCount} group(s) for {$year}, and created new cycles with duplicated milestone templates.");
        }

        // Single stage archival
        $stage = CapstoneStages::where('id', $stageId)->where('is_archived', false)->first();

        if (!$stage) {
            return back()->withErrors(['archive' => 'The selected Capstone stage is already archived or does not exist.']);
        }

        // Find all active groups pointing to this stage
        $groupsToArchive = Group::where('capstone_stage_id', $stage->id)->where('is_archived', false)->get();

        // Mark the stage as archived
        $stage->is_archived = true;
        $stage->archived_year = $year;
        $stage->is_enabled = false;
        $stage->save();

        // Archive all groups of this stage and propagate to teachers/sections
        foreach ($groupsToArchive as $g) {
            $g->is_archived = true;
            $g->archived_year = $year;
            $g->save();

            // Check adviser of this archived group
            $adviserId = $g->adviser_id;
            $hasActiveGroups = Group::where('adviser_id', $adviserId)->where('is_archived', false)->exists();
            if (!$hasActiveGroups) {
                Teacher::where('id', $adviserId)->update(['is_archived' => true]);
            }

            // Check section of this archived group
            $secId = $g->section_id;
            $hasActiveGroupsSec = Group::where('section_id', $secId)->where('is_archived', false)->exists();
            if (!$hasActiveGroupsSec) {
                Section::where('id', $secId)->update(['is_archived' => true]);
            }
        }

        // Archive all students in these groups
        $archivedGroupIds = $groupsToArchive->pluck('id')->toArray();
        $studentUserIds = TeamMember::whereIn('group_id', $archivedGroupIds)->pluck('user_id')->toArray();
        Student::whereIn('user_id', $studentUserIds)->update(['is_archived' => true]);

        // Create new active cycle stage
        $clonedTitle = $request->new_title ?: "Capstone {$stage->stage_type} - Cycle {$year}";
        $newStage = CapstoneStages::create([
            'stage_title'   => $clonedTitle,
            'is_enabled'    => true, // Enable the newly created stage
            'is_archived'   => false,
            'stage_type'    => $stage->stage_type,
        ]);

        // Enforce only this stage is enabled (disable all others)
        CapstoneStages::where('id', '!=', $newStage->id)->update(['is_enabled' => false]);

        // Duplicate milestones
        $oldMilestones = Milestone::where('capstone_stage_id', $stage->id)->get();

        // Delete active classrooms associated with the archived milestones of this stage
        $oldMilestoneIds = $oldMilestones->pluck('id')->toArray();
        EvaluationRoom::whereIn('required_milestone_id', $oldMilestoneIds)->delete();

        foreach ($oldMilestones as $om) {
            Milestone::create([
                'milestone_title'       => $om->milestone_title,
                'milestone_description' => $om->milestone_description,
                'capstone_stage_id'     => $newStage->id,
                'start_date'            => $om->start_date,
                'due_date'              => $om->due_date,
                'step_order'            => $om->step_order,
            ]);
        }

        return back()->with('success', "Successfully archived the cycle under '{$stage->stage_title}' for {$year}, archived " . $groupsToArchive->count() . " group(s), and created a new active cycle '{$newStage->stage_title}' with duplicated milestone templates.");
    }
}