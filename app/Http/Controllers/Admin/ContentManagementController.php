<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Subject;
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
        $subjects = Subject::orderBy('order')->paginate(15);

        return view('admin.content.subjects', compact('subjects'));
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
