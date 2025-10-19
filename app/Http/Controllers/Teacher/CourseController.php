<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:teacher']);
    }

    public function index()
    {
        $teacher = Auth::user();
        $courses = $teacher->createdCourses()
            ->with(['curriculum.subject', 'curriculum.level'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('teacher.courses.index', compact('teacher', 'courses'));
    }

    public function create()
    {
        $teacher = Auth::user();
        $curricula = Curriculum::with(['subject', 'level'])
            ->active()
            ->current()
            ->get();

        return view('teacher.courses.create', compact('teacher', 'curricula'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'curriculum_id' => 'required|exists:curricula,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $course = Course::create(array_merge($validated, [
            'created_by' => Auth::id(),
            'is_approved' => false, // Требует модерации
        ]));

        return redirect()->route('teacher.courses.show', $course)
            ->with('success', 'Курс создан успешно! Ожидает модерации.');
    }

    public function show(Course $course)
    {
        // Проверяем, что курс принадлежит учителю
        if ($course->created_by !== Auth::id()) {
            abort(403);
        }

        $course->load(['curriculum.subject', 'curriculum.level', 'lessons']);

        return view('teacher.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        if ($course->created_by !== Auth::id()) {
            abort(403);
        }

        $curricula = Curriculum::with(['subject', 'level'])
            ->active()
            ->current()
            ->get();

        return view('teacher.courses.edit', compact('course', 'curricula'));
    }

    public function update(Request $request, Course $course)
    {
        if ($course->created_by !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $course->update($validated);

        return redirect()->route('teacher.courses.show', $course)
            ->with('success', 'Курс обновлен успешно!');
    }
}
