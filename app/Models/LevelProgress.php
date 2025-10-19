<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'subject_id', 'level_id', 'status',
        'progress_percentage', 'score', 'started_at', 'completed_at', 'assessment_results'
    ];

    protected $casts = [
        'assessment_results' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    // Отношения
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function curriculumProgress()
    {
        return $this->hasMany(CurriculumProgress::class);
    }

    // Методы
    public function start()
    {
        if ($this->status === 'not_started') {
            $this->update([
                'status' => 'in_progress',
                'started_at' => now()
            ]);
        }
    }

    public function complete($score = null)
    {
        $this->update([
            'status' => 'completed',
            'score' => $score,
            'completed_at' => now(),
            'progress_percentage' => 100
        ]);

        $this->unlockNextLevels();
    }

    private function unlockNextLevels()
    {
        $nextLevels = $this->level->nextLevels;

        foreach ($nextLevels as $nextLevel) {
            LevelProgress::firstOrCreate([
                'student_id' => $this->student_id,
                'subject_id' => $this->subject_id,
                'level_id' => $nextLevel->id
            ], [
                'status' => $nextLevel->isAccessibleForStudent($this->student_id, $this->subject_id)
                    ? 'not_started'
                    : 'locked'
            ]);
        }
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }
}
