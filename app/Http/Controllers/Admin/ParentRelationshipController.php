<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentStudentRelationship;
use App\Models\User;
use Illuminate\Http\Request;

class ParentRelationshipController extends Controller
{
    public function index(Request $request)
    {
        $query = ParentStudentRelationship::with(['parent', 'student']);

        // Фильтрация по родителю
        if ($request->filled('parent_search')) {
            $query->whereHas('parent', function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->parent_search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->parent_search . '%')
                    ->orWhere('email', 'like', '%' . $request->parent_search . '%');
            });
        }

        // Фильтрация по студенту
        if ($request->filled('student_search')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->student_search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->student_search . '%')
                    ->orWhere('email', 'like', '%' . $request->student_search . '%');
            });
        }

        $relationships = $query->orderBy('created_at', 'desc')->paginate(20);

        // Статистика
        $totalRelationships = ParentStudentRelationship::count();
        $parentsWithChildren = User::whereHas('roles', function($q) {
            $q->where('name', 'parent');
        })->whereHas('children')->count();
        $studentsWithParents = User::whereHas('roles', function($q) {
            $q->where('name', 'student');
        })->whereHas('parents')->count();
        $studentsWithoutParents = User::whereHas('roles', function($q) {
            $q->where('name', 'student');
        })->whereDoesntHave('parents')->count();

        return view('admin.parent-relationships.index', compact(
            'relationships',
            'totalRelationships',
            'parentsWithChildren',
            'studentsWithParents',
            'studentsWithoutParents'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'required|exists:users,id',
            'student_id' => 'required|exists:users,id',
            'relationship_type' => 'required|in:parent,guardian,relative',
            'is_primary' => 'boolean',
            'can_view_progress' => 'boolean',
            'can_receive_notifications' => 'boolean',
        ]);

        try {
            // Проверяем, что родитель имеет роль parent
            $parent = User::find($validated['parent_id']);
            if (!$parent->hasRole('parent')) {
                return back()->with('error', 'Выбранный пользователь не является родителем.');
            }

            // Проверяем, что студент имеет роль student
            $student = User::find($validated['student_id']);
            if (!$student->hasRole('student')) {
                return back()->with('error', 'Выбранный пользователь не является студентом.');
            }

            // Проверяем, что связь не существует
            if (ParentStudentRelationship::where('parent_id', $validated['parent_id'])
                ->where('student_id', $validated['student_id'])->exists()) {
                return back()->with('error', 'Связь между этими пользователями уже существует.');
            }

            ParentStudentRelationship::create($validated);

            return redirect()->route('admin.parent-relationships.index')
                ->with('success', 'Связь успешно создана.');

        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при создании связи: ' . $e->getMessage());
        }
    }

    public function update(Request $request, ParentStudentRelationship $relationship)
    {
        $validated = $request->validate([
            'relationship_type' => 'required|in:parent,guardian,relative',
            'is_primary' => 'boolean',
            'can_view_progress' => 'boolean',
            'can_receive_notifications' => 'boolean',
        ]);

        try {
            $relationship->update($validated);

            return redirect()->route('admin.parent-relationships.index')
                ->with('success', 'Связь успешно обновлена.');

        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при обновлении связи: ' . $e->getMessage());
        }
    }

    public function destroy(ParentStudentRelationship $relationship)
    {
        try {
            $relationship->delete();

            return redirect()->route('admin.parent-relationships.index')
                ->with('success', 'Связь успешно удалена.');

        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при удалении связи: ' . $e->getMessage());
        }
    }
}
