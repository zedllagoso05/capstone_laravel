<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\user_controller;

/*
|--------------------------------------------------------------------------
| Home Route – Guest only, redirects logged‑in users to their dashboard
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin'   => redirect()->route('admin.page'),
            'teacher' => redirect()->route('teacher.page'),
            'student' => redirect()->route('student.page'),
            default   => redirect()->route('home.page'),
        };
    }
    return view('home', ['greetings' => 'Hello, welcome to the home page!']);
})->name('login');

// Redirect any GET requests to /login back to the main home page
Route::redirect('/login', '/');

/*
|--------------------------------------------------------------------------
| Authentication Routes – Public
|--------------------------------------------------------------------------
*/
Route::post('/register',   [user_controller::class, 'register']);
Route::post('/login',      [user_controller::class, 'login']);
Route::post('/logout',     [user_controller::class, 'logout'])->name('logout');
Route::post('/id',         [user_controller::class, 'id']);
Route::post('/destroy',    [user_controller::class, 'destroy'])->name('destroy.session');
Route::post('/send-code',  [user_controller::class, 'sendVerificationCode'])
    ->name('send_code')
    ->middleware('throttle:5,1');

Route::get('/forgot-password',          [user_controller::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password/send',    [user_controller::class, 'sendForgotPasswordCode'])->name('password.email');
Route::post('/forgot-password/reset',   [user_controller::class, 'resetPasswordWithCode'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Protected Routes – Authentication Required
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Global Routes (accessible by any authenticated user)
    |--------------------------------------------------------------------------
    */
    Route::get('/verify-email',          [user_controller::class, 'showVerifyEmailForm'])->name('verification.notice');
    Route::post('/verify-email/send',    [user_controller::class, 'sendVerificationCodeAfterLogin'])->name('verification.send_code');
    Route::post('/verify-email/confirm', [user_controller::class, 'confirmVerificationCode'])->name('verification.confirm');

    Route::get('/certificate/{groupId}/{certificateId}', [user_controller::class, 'showCertificate'])->name('certificate.show');
    Route::get('/group/{groupId}/certificates',          [user_controller::class, 'getGroupCertificates'])->name('group.certificates');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes – Role: admin
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {

        // Teacher management
        Route::post('/add_teacher',   [user_controller::class, 'addTeacher'])->name('admin.add_teacher');
        Route::post('/edit_teacher',  [user_controller::class, 'editTeacher'])->name('admin.edit_teacher');
        Route::post('/delete_teacher',[user_controller::class, 'deleteTeacher'])->name('admin.delete_teacher');

        // Student management
        Route::post('/add_student',   [user_controller::class, 'addStudent'])->name('admin.add_student');
        Route::post('/edit_student',  [user_controller::class, 'editStudent'])->name('admin.edit_student');
        Route::post('/delete_student',[user_controller::class, 'deleteStudent'])->name('admin.delete_student');

        // Rubrics & Milestones
        Route::post('/add_rubric',    [user_controller::class, 'addRubric'])->name('admin.add_rubric');
        Route::get('/get-rubric/{id}',[user_controller::class, 'getRubric'])->name('admin.get_rubric');
        Route::put('/update-rubric/{id}', [user_controller::class, 'updateRubric'])->name('admin.update_rubric');
        Route::post('/delete-rubrics',[user_controller::class, 'deleteRubrics'])->name('admin.delete_rubrics');

        Route::post('/add_milestone', [user_controller::class, 'addMilestone'])->name('admin.add_milestone');
        Route::get('/get-milestone/{id}', [user_controller::class, 'getMilestone'])->name('admin.get_milestone');
        Route::put('/update-milestone/{id}', [user_controller::class, 'updateMilestone'])->name('admin.update_milestone');

        // Groups & Advisers
        Route::post('/assign-group',  [user_controller::class, 'assignGroups'])->name('admin.assign_group');
        Route::post('/assign-section',[user_controller::class, 'assignSection'])->name('admin.assign_section');
        Route::post('/create-group',  [user_controller::class, 'createGroup'])->name('admin.create_group');
        Route::get('/get-group/{id}', [user_controller::class, 'getGroupAdmin'])->name('admin.get_group');
        Route::get('/get-group-progress/{groupId}', [user_controller::class, 'getGroupProgress'])->name('admin.get_group_progress');
        Route::put('/update-group/{id}', [user_controller::class, 'updateGroupAdmin'])->name('admin.update_group');
        Route::get('/get-students/{section}', [user_controller::class, 'getStudentsBySection'])->name('admin.get-students');

        // Teacher assignments & lookups
        Route::get('/teacher-groups/{teacherId}',    [user_controller::class, 'getTeacherGroups'])->name('admin.teacher_groups');
        Route::get('/teacher-sections/{teacherId}',  [user_controller::class, 'getTeacherSections'])->name('admin.teacher.sections');

        // Bulk import
        Route::post('/import-students',  [user_controller::class, 'importStudents'])->name('admin.import_students');
        Route::get('/download-student-template', [user_controller::class, 'downloadStudentTemplate'])->name('admin.download_student_template');
        Route::post('/import-teachers',  [user_controller::class, 'importTeachers'])->name('admin.import_teachers');
        Route::get('/download-teacher-template', [user_controller::class, 'downloadTeacherTemplate'])->name('admin.download_teacher_template');

        // Evaluation rooms
        Route::post('/evaluation-rooms',        [user_controller::class, 'createRoom'])->name('admin.create_room');
        Route::get('/evaluation-rooms', function () {
            return redirect()->route('admin.page');
        });
        Route::get('/get-room/{room}',          [user_controller::class, 'getRoom'])->name('admin.get_room');
        Route::post('/evaluation-rooms/{room}/panelists', [user_controller::class, 'addPanelist'])->name('admin.add_panelist');
        Route::delete('/evaluation-rooms/{room}/panelists/{teacher}', [user_controller::class, 'removePanelist'])->name('admin.remove_panelist');
        Route::post('/delete_room', [user_controller::class, 'deleteRoom'])->name('admin.delete_room');
        Route::post('/evaluation-rooms/{roomId}/regenerate-code', [user_controller::class, 'regenerateRoomCode'])->name('admin.regenerate_room_code');

        // Admin profile
        Route::post('/profile_update',       [user_controller::class, 'adminProfileUpdate'])->name('admin.profile_update');
        Route::post('/update-password',      [user_controller::class, 'updatePassword'])->name('admin.profile.update_password');

        // Capstone management
        Route::post('/capstone/toggle-stage', [user_controller::class, 'toggleCapstoneStage'])->name('admin.toggle_capstone_stage');
        Route::post('/capstone/archive',      [user_controller::class, 'archiveCapstoneByYear'])->name('admin.archive_capstone_by_year');
    });

    /*
    |--------------------------------------------------------------------------
    | Teacher Routes – Role: teacher
    |--------------------------------------------------------------------------
    */
    Route::prefix('teacher')->middleware(['role:teacher'])->group(function () {

        // Profile
        Route::post('/profile_update',    [user_controller::class, 'profileUpdate'])->name('teacher.profile_update');
        Route::post('/update-password',   [user_controller::class, 'updatePassword'])->name('teacher.update_password');

        // Students & Groups (teacher's own)
        Route::get('/get-students/{section}', [user_controller::class, 'getStudentsBySection'])->name('teacher.get_students');
        Route::post('/create-group',          [user_controller::class, 'createGroup'])->name('teacher.create_group');
        Route::get('/get-group/{group}',      [user_controller::class, 'getGroupDetails'])->name('teacher.get_group');
        Route::post('/update-group/{group}',  [user_controller::class, 'updateGroup'])->name('teacher.update_group');

        // Rubrics & evaluations
        Route::get('/get-rubric/{milestone}', [user_controller::class, 'getRubricForMilestone'])->name('teacher.rubric');
        Route::post('/submit-evaluation',     [user_controller::class, 'submitEvaluation'])->name('teacher.submit_evaluation');

        // Remark evaluation (adviser or panelist)
        Route::post('/evaluate-remark', [user_controller::class, 'evaluateMilestoneRemark'])->name('teacher.evaluate_remark');

        // Milestone evaluation status
        Route::get('/get-evaluated-milestones/{group}', [user_controller::class, 'getEvaluatedMilestones'])->name('teacher.evaluated_milestones');

        // Group progress (view modal)
        Route::get('/get-group-progress/{group}', [user_controller::class, 'getGroupProgress'])->name('teacher.group_progress');

        // Join an evaluation room as panelist
        Route::post('/join-room', [user_controller::class, 'joinRoomWithCode'])->name('teacher.join_room');
    });

    /*
    |--------------------------------------------------------------------------
    | Student Routes – Role: student
    |--------------------------------------------------------------------------
    */
    Route::prefix('sections')->middleware(['role:student'])->group(function () {
        Route::get('/student', [user_controller::class, 'dashboard'])->name('student.page');
    });

    Route::prefix('student')->middleware(['role:student'])->group(function () {
        Route::post('/update',               [user_controller::class, 'update'])->name('student.profile.update');
        Route::post('/update-password',      [user_controller::class, 'updatePassword'])->name('student.profile.update_password');
        Route::get('/get-group-progress/{group}', [user_controller::class, 'getGroupProgress'])->name('student.group_progress');
    });

    /*
    |--------------------------------------------------------------------------
    | Public Dashboard Access (roles already checked by controller)
    |--------------------------------------------------------------------------
    */
    Route::get('/sections/admin',   [user_controller::class, 'adminDashboard'])->name('admin.page');
    Route::get('/sections/teacher', [user_controller::class, 'teacherDashboard'])->name('teacher.page');
});