<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\Level;
use Illuminate\Http\Request;

class ContentManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function courses()
    {
        $courses = Course::with(['curriculum.subject', 'curriculum.level', 'author'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.content.courses', compact('courses'));
    }

    public function approveCourse(Course $course)
    {
        $course->update([
            'is_approved' => true,
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Курс одобрен и активирован!');
    }

    public function rejectCourse(Course $course)
    {
        $course->update([
            'is_approved' => false,
            'is_active' => false
        ]);

        return redirect()->back()->with('success', 'Курс отклонен!');
    }

    public function subjects()
    {
        $subjects = Subject::withCount(['courses', 'levels'])
            ->orderBy('order')
            ->paginate(20);

        $totalSubjects = Subject::count();
        $activeSubjects = Subject::where('is_active', true)->count();
        $totalCourses = Course::count();
        $totalLevels = Level::count();

        return view('admin.content.subjects', compact(
            'subjects',
            'totalSubjects',
            'activeSubjects',
            'totalCourses',
            'totalLevels'
        ));
    }

    public function storeSubject(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Предмет успешно создан.');
    }

    public function updateSubject(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $subject->update($validated);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Предмет успешно обновлен.');
    }

    public function activateSubject(Subject $subject)
    {
        $subject->update(['is_active' => true]);

        return back()->with('success', 'Предмет активирован.');
    }

    public function deactivateSubject(Subject $subject)
    {
        $subject->update(['is_active' => false]);

        return back()->with('success', 'Предмет деактивирован.');
    }

    public function updateSubjectOrder(Request $request)
    {
        $request->validate([
            'subjects' => 'required|array',
        ]);

        foreach ($request->subjects as $order => $subjectId) {
            Subject::where('id', $subjectId)->update(['order' => $order + 1]);
        }

        return response()->json(['success' => true]);
    }
}
