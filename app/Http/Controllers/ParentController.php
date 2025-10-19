<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LevelProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:parent']);
    }

    public function dashboard()
    {
        $parent = Auth::user();
        $children = $parent->children()->with(['levelProgress.subject'])->get();

        return view('parent.dashboard', compact('children'));
    }

    public function childProgress($childId)
    {
        $parent = Auth::user();
        $child = $parent->children()->findOrFail($childId);

        // Проверяем права доступа
        if (!$parent->canViewStudentProgress($childId)) {
            abort(403, 'Доступ к прогрессу ребенка запрещен');
        }

        $progress = $child->levelProgress()
            ->with(['subject', 'level'])
            ->orderBy('created_at', 'desc')
            ->get();

        $overallProgress = $this->calculateOverallProgress($child);

        return view('parent.child-progress', compact('child', 'progress', 'overallProgress'));
    }

    public function addChild(Request $request)
    {
        $request->validate([
            'student_email' => 'required|email|exists:users,email',
            'relationship_type' => 'required|in:mother,father,guardian,other',
            'is_primary' => 'boolean'
        ]);

        $student = User::where('email', $request->student_email)->firstOrFail();

        // Проверяем, что пользователь действительно студент
        if (!$student->isStudent()) {
            return back()->with('error', 'Пользователь не является студентом');
        }

        // Создаем связь
        $success = Auth::user()->addChild(
            $student->id,
            $request->relationship_type,
            $request->is_primary ?? false
        );

        if ($success) {
            return back()->with('success', 'Связь с учеником установлена. Ожидает подтверждения.');
        } else {
            return back()->with('error', 'Не удалось установить связь с учеником.');
        }
    }

    private function calculateOverallProgress($student)
    {
        $totalProgress = $student->levelProgress()
            ->where('status', 'completed')
            ->avg('progress_percentage');

        $completedLevels = $student->levelProgress()
            ->where('status', 'completed')
            ->count();

        $inProgress = $student->levelProgress()
            ->where('status', 'in_progress')
            ->count();

        return [
            'total_progress' => round($totalProgress ?? 0, 1),
            'completed_levels' => $completedLevels,
            'in_progress' => $inProgress,
            'average_score' => round($student->levelProgress()->avg('score') ?? 0, 1)
        ];
    }
}
