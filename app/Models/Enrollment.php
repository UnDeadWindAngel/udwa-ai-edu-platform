<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'course_id', 'status', 'enrolled_at', 'completed_at', 'progress_percentage'
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_DROPPED = 'dropped';
    const STATUS_SUSPENDED = 'suspended';

    // Отношения
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    // Методы
    public function complete()
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
            'progress_percentage' => 100
        ]);
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function calculateProgress()
    {
        $totalLessons = $this->course->lessons->count();
        if ($totalLessons === 0) return 0;

        $completedLessons = StudentProgress::where('student_id', $this->student_id)
            ->whereIn('lesson_id', $this->course->lessons->pluck('id'))
            ->where('completed', true)
            ->count();

        $progress = ($completedLessons / $totalLessons) * 100;

        $this->update(['progress_percentage' => round($progress, 2)]);

        return $progress;
    }
}
