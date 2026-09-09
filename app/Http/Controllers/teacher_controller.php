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
use App\Models\CapstoneYear;
use Illuminate\Support\Facades\Hash;
use App\Imports\StudentsImport;
use App\Imports\TeachersImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon; // Add this at the top of your controller
use App\Services\Mailer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

 class teacher_controller extends Controller
{

}