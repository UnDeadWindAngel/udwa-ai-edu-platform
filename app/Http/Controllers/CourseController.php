<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['curriculum.subject', 'curriculum.level', 'author'])
            ->active()
            ->approved()
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $course->load(['curriculum.subject', 'curriculum.level', 'lessons', 'author']);

        $user = Auth::user();
        $isEnrolled = false;
        $enrollment = null;

        if ($user && $user->isStudent()) {
            $enrollment = Enrollment::where('student_id', $user->id)
                ->where('course_id', $course->id)
                ->first();
            $isEnrolled = !is_null($enrollment);
        }

        return view('courses.show', compact('course', 'isEnrolled', 'enrollment'));
    }

    public function enroll(Course $course)
    {
        $user = Auth::user();

        if (!$user->isStudent()) {
            return redirect()->back()->with('error', 'Только студенты могут записываться на курсы');
        }

        // Проверяем, не записан ли уже
        $existingEnrollment = Enrollment::where('student_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()->with('info', 'Вы уже записаны на этот курс');
        }

        // Создаем запись
        Enrollment::create([
            'student_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'active',
            'enrolled_at' => now()
        ]);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Вы успешно записались на курс!');
    }
}
