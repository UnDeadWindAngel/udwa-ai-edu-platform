<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'lesson_id', 'completed', 'time_spent',
        'score', 'started_at', 'completed_at', 'progress_data'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress_data' => 'array'
    ];

    // Отношения
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    public function scopeInProgress($query)
    {
        return $query->where('completed', false)->whereNotNull('started_at');
    }

    // Методы
    public function start()
    {
        if (is_null($this->started_at)) {
            $this->update(['started_at' => now()]);
        }
    }

    public function complete($score = null)
    {
        $this->update([
            'completed' => true,
            'score' => $score,
            'completed_at' => now()
        ]);

        // Обновляем прогресс в enrollment
        $enrollment = Enrollment::where('student_id', $this->student_id)
            ->where('course_id', $this->lesson->course_id)
            ->first();

        if ($enrollment) {
            $enrollment->calculateProgress();
        }
    }

    public function addTimeSpent($seconds)
    {
        $this->increment('time_spent', $seconds);
    }

    public function getTimeSpentFormattedAttribute()
    {
        $hours = floor($this->time_spent / 3600);
        $minutes = floor(($this->time_spent % 3600) / 60);
        $seconds = $this->time_spent % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
