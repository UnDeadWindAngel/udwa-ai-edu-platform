<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;
use App\Models\Course;
use App\Models\LevelProgress;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_teachers' => User::whereHas('roles', function($q) {
                $q->where('name', 'teacher');
            })->count(),
            'total_students' => User::whereHas('roles', function($q) {
                $q->where('name', 'student');
            })->count(),
            'total_parents' => User::whereHas('roles', function($q) {
                $q->where('name', 'parent');
            })->count(),
            'total_subjects' => Subject::count(),
            'total_courses' => Course::count(),
            'active_courses' => Course::where('is_active', true)->count(),
            'pending_approvals' => Course::where('is_approved', false)->count(),
        ];

        // Последние активные студенты
        $recentStudents = User::whereHas('roles', function($q) {
            $q->where('name', 'student');
        })->with('levelProgress')
            ->orderBy('last_login_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentStudents'));
    }
}
