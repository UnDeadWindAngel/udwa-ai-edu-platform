<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\StudentProgress;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson)
    {
        // Проверяем, что урок принадлежит курсу
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $lesson->load(['slides', 'course']);

        $user = Auth::user();
        $progress = null;
        $nextLesson = $lesson->getNextLesson();
        $previousLesson = $lesson->getPreviousLesson();

        if ($user && $user->isStudent()) {
            // Получаем или создаем прогресс
            $progress = StudentProgress::firstOrCreate([
                'student_id' => $user->id,
                'lesson_id' => $lesson->id
            ]);

            // Если урок еще не начат - начинаем
            if (is_null($progress->started_at)) {
                $progress->start();
            }
        }

        return view('lessons.show', compact('course', 'lesson', 'progress', 'nextLesson', 'previousLesson'));
    }

    public function complete(Request $request, Course $course, Lesson $lesson)
    {
        $user = Auth::user();

        if (!$user->isStudent()) {
            return redirect()->back()->with('error', 'Только студенты могут завершать уроки');
        }

        $progress = StudentProgress::where('student_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->firstOrFail();

        $progress->complete($request->score);

        return redirect()->back()->with('success', 'Урок завершен!');
    }

    public function updateProgress(Request $request, Course $course, Lesson $lesson)
    {
        $user = Auth::user();

        if (!$user->isStudent()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $progress = StudentProgress::where('student_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->firstOrFail();

        $progress->addTimeSpent($request->seconds);

        return response()->json(['success' => true]);
    }
}
