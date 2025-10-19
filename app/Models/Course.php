<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'curriculum_id', 'title', 'slug', 'description', 'image',
        'order', 'is_active', 'is_approved', 'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_approved' => 'boolean'
    ];

    // Автоматическое создание slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Отношения
    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subject()
    {
        return $this->through('curriculum')->has('subject');
    }

    public function level()
    {
        return $this->through('curriculum')->has('level');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Методы
    public function getTotalLessonsAttribute()
    {
        return $this->lessons->count();
    }

    public function getTotalStudentsAttribute()
    {
        return $this->enrollments()->where('status', 'active')->count();
    }
}
