<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id', 'level_id', 'name', 'version', 'description',
        'total_hours', 'theory_hours', 'practice_hours', 'weekly_schedule',
        'effective_from', 'effective_to', 'is_active', 'created_by'
    ];

    protected $casts = [
        'weekly_schedule' => 'array',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_active' => 'boolean'
    ];

    // Отношения
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function modules()
    {
        return $this->hasMany(CurriculumModule::class)->orderBy('order');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where('effective_from', '<=', now())
            ->where(function($q) {
                $q->where('effective_to', '>=', now())
                    ->orWhereNull('effective_to');
            });
    }

    // Методы
    public function getTotalTopicsAttribute()
    {
        return $this->modules->sum(function($module) {
            return $module->topics->count();
        });
    }

    public function isCurrent()
    {
        return $this->effective_from <= now() &&
            ($this->effective_to === null || $this->effective_to >= now());
    }
}
