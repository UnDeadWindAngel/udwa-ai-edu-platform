<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'curriculum_id', 'name', 'description', 'order',
        'total_hours', 'theory_hours', 'practice_hours', 'learning_objectives'
    ];

    protected $casts = [
        'learning_objectives' => 'array'
    ];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function topics()
    {
        return $this->hasMany(CurriculumTopic::class)->orderBy('order');
    }

    public function getCompletionPercentageAttribute()
    {
        $totalTopics = $this->topics->count();
        if ($totalTopics === 0) return 0;

        $completedTopics = $this->topics->where('completed', true)->count();
        return ($completedTopics / $totalTopics) * 100;
    }
}
