<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'color', 'icon',
        'order', 'is_active', 'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array'
    ];

    // Автоматическое создание slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subject) {
            if (empty($subject->slug)) {
                $subject->slug = Str::slug($subject->name);
            }
        });

        static::updating(function ($subject) {
            if ($subject->isDirty('name') && empty($subject->slug)) {
                $subject->slug = Str::slug($subject->name);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Отношения
    public function curricula()
    {
        return $this->hasMany(Curriculum::class);
    }

    public function courses()
    {
        return $this->hasManyThrough(Course::class, Curriculum::class);
    }

    public function activeCurricula()
    {
        return $this->hasMany(Curriculum::class)->where('is_active', true);
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'curricula')
            ->withPivot('total_hours', 'theory_hours', 'practice_hours')
            ->withTimestamps();
    }
}
