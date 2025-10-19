<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\LevelProgress;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Показ основной страницы приложения.
     */
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Разная логика для разных ролей
            if ($user->isStudent()) {
                return $this->studentDashboard($user);
            } elseif ($user->isTeacher()) {
                return $this->teacherDashboard($user);
            } elseif ($user->isParent()) {
                return $this->parentDashboard($user);
            } elseif ($user->isAdmin() || $user->isModerator()) {
                return $this->adminDashboard($user);
            }
        }

        // Для гостей - лендинг страница
        return view('home');
    }

    /**
     * Дашборд для студента
     */
    private function studentDashboard($user)
    {
        $progress = LevelProgress::where('student_id', $user->id)
            ->with(['subject', 'level'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $enrollments = Enrollment::where('student_id', $user->id)
            ->with('course')
            ->active()
            ->get();

        $recentSubjects = Subject::where('is_active', true)
            ->orderBy('order')
            ->limit(3)
            ->get();

        return view('home', compact('user', 'progress', 'enrollments', 'recentSubjects'));
    }

    /**
     * Дашборд для учителя
     */
    private function teacherDashboard($user)
    {
        $createdCourses = $user->createdCourses()
            ->withCount('enrollments')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $totalStudents = Enrollment::whereIn('course_id', $user->createdCourses()->pluck('id'))
            ->active()
            ->count();

        return view('home', compact('user', 'createdCourses', 'totalStudents'));
    }

    /**
     * Дашборд для родителя
     */
    private function parentDashboard($user)
    {
        $children = $user->children()
            ->with(['levelProgress' => function($query) {
                $query->with(['subject', 'level'])
                    ->orderBy('updated_at', 'desc')
                    ->limit(3);
            }])
            ->get();

        return view('home', compact('user', 'children'));
    }

    /**
     * Дашборд для администратора/модератора
     */
    private function adminDashboard($user)
    {
        $stats = [
            'total_users' => User::count(),
            'total_subjects' => Subject::count(),
            'active_courses' => Course::active()->count(),
            'pending_approvals' => Course::where('is_approved', false)->count(),
        ];

        return view('home', compact('user', 'stats'));
    }

    /**
     * Страница прогресса (для студентов)
     */
    public function progress()
    {
        $user = Auth::user();

        if (!$user->isStudent()) {
            return redirect()->route('home')->with('error', 'Эта страница только для студентов');
        }

        $progress = LevelProgress::where('student_id', $user->id)
            ->with(['subject', 'level'])
            ->orderBy('created_at', 'desc')
            ->get();

        $overallProgress = $this->calculateOverallProgress($user);

        return view('progress', compact('user', 'progress', 'overallProgress'));
    }

    /**
     * Расчет общего прогресса студента
     */
    private function calculateOverallProgress($user)
    {
        $totalProgress = LevelProgress::where('student_id', $user->id)
            ->where('status', 'completed')
            ->avg('progress_percentage');

        $completedLevels = LevelProgress::where('student_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $inProgress = LevelProgress::where('student_id', $user->id)
            ->where('status', 'in_progress')
            ->count();

        return [
            'total_progress' => round($totalProgress ?? 0, 1),
            'completed_levels' => $completedLevels,
            'in_progress' => $inProgress,
            'average_score' => round(LevelProgress::where('student_id', $user->id)->avg('score') ?? 0, 1)
        ];
    }
}
