<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Level;
use App\Models\LevelProgress;
use App\Models\Curriculum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    public function show(Subject $subject, Level $level)
    {
        $user = Auth::user();

        // Проверяем доступность уровня
        if (!$level->isAccessibleForStudent($user->id, $subject->id)) {
            return redirect()->route('subjects.levels', $subject)
                ->with('error', 'Уровень недоступен. Завершите предыдущие уровни.');
        }

        // Получаем учебный план для этого уровня
        $curriculum = Curriculum::where('subject_id', $subject->id)
            ->where('level_id', $level->id)
            ->where('is_active', true)
            ->with(['modules.topics'])
            ->first();

        // Создаем или получаем прогресс
        $progress = LevelProgress::firstOrCreate([
            'student_id' => $user->id,
            'subject_id' => $subject->id,
            'level_id' => $level->id
        ]);

        // Если уровень еще не начат - начинаем
        if ($progress->status === 'not_started') {
            $progress->start();
        }

        return view('levels.show', compact('subject', 'level', 'curriculum', 'progress'));
    }

    public function start(Subject $subject, Level $level)
    {
        $user = Auth::user();

        if (!$level->isAccessibleForStudent($user->id, $subject->id)) {
            return redirect()->back()
                ->with('error', 'Уровень недоступен. Завершите предыдущие уровни.');
        }

        $progress = LevelProgress::firstOrCreate([
            'student_id' => $user->id,
            'subject_id' => $subject->id,
            'level_id' => $level->id
        ]);

        $progress->start();

        return redirect()->route('levels.show', [$subject, $level])
            ->with('success', 'Уровень начат!');
    }
}
