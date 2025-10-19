<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'order', 'min_hours', 'max_hours',
        'learning_outcomes', 'is_active', 'prerequisite_level_id', 'min_score',
        'requires_completion', 'completion_criteria'
    ];

    protected $casts = [
        'learning_outcomes' => 'array',
        'completion_criteria' => 'array',
        'is_active' => 'boolean',
        'requires_completion' => 'boolean'
    ];

    // Отношения
    public function prerequisite()
    {
        return $this->belongsTo(Level::class, 'prerequisite_level_id');
    }

    public function nextLevels()
    {
        return $this->hasMany(Level::class, 'prerequisite_level_id');
    }

    public function prerequisites()
    {
        return $this->hasMany(LevelPrerequisite::class, 'level_id');
    }

    public function curricula()
    {
        return $this->hasMany(Curriculum::class);
    }

    public function levelProgress()
    {
        return $this->hasMany(LevelProgress::class);
    }

    // Методы проверки доступности
    public function isAccessibleForStudent($studentId, $subjectId)
    {
        if (!$this->requires_completion) {
            return true;
        }

        foreach ($this->prerequisites as $prerequisite) {
            $progress = LevelProgress::where('student_id', $studentId)
                ->where('subject_id', $subjectId)
                ->where('level_id', $prerequisite->required_level_id)
                ->first();

            if (!$progress || $progress->status !== 'completed' || $progress->score < $prerequisite->min_score) {
                return false;
            }
        }

        return true;
    }

    public function getStatusForStudent($studentId, $subjectId)
    {
        $progress = LevelProgress::where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->where('level_id', $this->id)
            ->first();

        if ($progress) {
            return $progress->status;
        }

        return $this->isAccessibleForStudent($studentId, $subjectId) ? 'not_started' : 'locked';
    }
}
