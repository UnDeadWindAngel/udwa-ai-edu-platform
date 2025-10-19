<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Level;
use App\Models\LevelProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('subjects.index', compact('subjects'));
    }

    public function show(Subject $subject)
    {
        $user = Auth::user();
        $levels = Level::whereHas('curricula', function($query) use ($subject) {
            $query->where('subject_id', $subject->id)->where('is_active', true);
        })
            ->orderBy('order')
            ->get()
            ->map(function($level) use ($user, $subject) {
                $level->status = $level->getStatusForStudent($user->id, $subject->id);
                $level->progress = LevelProgress::where('student_id', $user->id)
                    ->where('subject_id', $subject->id)
                    ->where('level_id', $level->id)
                    ->first();
                return $level;
            });

        return view('subjects.show', compact('subject', 'levels'));
    }

    public function levels(Subject $subject)
    {
        $user = Auth::user();

        $levels = Level::whereHas('curricula', function($query) use ($subject) {
            $query->where('subject_id', $subject->id)->where('is_active', true);
        })
            ->orderBy('order')
            ->get()
            ->map(function($level) use ($user, $subject) {
                $level->status = $level->getStatusForStudent($user->id, $subject->id);
                $level->progress = LevelProgress::where('student_id', $user->id)
                    ->where('subject_id', $subject->id)
                    ->where('level_id', $level->id)
                    ->first();
                return $level;
            });

        return view('subjects.levels', compact('subject', 'levels'));
    }
}
