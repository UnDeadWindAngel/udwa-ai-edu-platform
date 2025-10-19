<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\StudentProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:teacher']);
    }

    public function index()
    {
        $teacher = Auth::user();

        $stats = [
            'total_courses' => $teacher->createdCourses()->count(),
            'active_courses' => $teacher->createdCourses()->active()->count(),
            'total_students' => Enrollment::whereIn('course_id', $teacher->createdCourses()->pluck('id'))
                ->active()
                ->count(),
            'pending_approvals' => $teacher->createdCourses()->where('is_approved', false)->count(),
        ];

        $recentCourses = $teacher->createdCourses()
            ->withCount('enrollments')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $popularCourses = $teacher->createdCourses()
            ->withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->limit(5)
            ->get();

        return view('teacher.dashboard', compact('teacher', 'stats', 'recentCourses', 'popularCourses'));
    }

    public function students()
    {
        $teacher = Auth::user();

        $students = Enrollment::whereIn('course_id', $teacher->createdCourses()->pluck('id'))
            ->with(['student', 'course'])
            ->active()
            ->get()
            ->groupBy('student_id');

        return view('teacher.students', compact('teacher', 'students'));
    }
}
