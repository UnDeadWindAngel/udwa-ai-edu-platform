<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'curriculum_topic_id', 'title', 'description', 'content',
        'order', 'estimated_duration', 'difficulty_level', 'learning_objectives',
        'is_published', 'requires_approval'
    ];

    protected $casts = [
        'learning_objectives' => 'array',
        'is_published' => 'boolean',
        'requires_approval' => 'boolean'
    ];

    const DIFFICULTY_EASY = 'easy';
    const DIFFICULTY_MEDIUM = 'medium';
    const DIFFICULTY_HARD = 'hard';

    // Отношения
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function curriculumTopic()
    {
        return $this->belongsTo(CurriculumTopic::class);
    }

    public function slides()
    {
        return $this->hasMany(LessonSlide::class)->orderBy('slide_number');
    }

    public function studentProgress()
    {
        return $this->hasMany(StudentProgress::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('requires_approval', false);
    }

    // Методы
    public function getTotalSlidesAttribute()
    {
        return $this->slides->count();
    }

    public function getTotalDurationAttribute()
    {
        return $this->slides->sum('duration');
    }

    public function isInteractive()
    {
        return $this->slides()->whereIn('slide_type', ['quiz', 'code', 'interactive'])->exists();
    }

    public function getNextLesson()
    {
        return $this->course->lessons()
            ->where('order', '>', $this->order)
            ->orderBy('order')
            ->first();
    }

    public function getPreviousLesson()
    {
        return $this->course->lessons()
            ->where('order', '<', $this->order)
            ->orderBy('order', 'desc')
            ->first();
    }
}
