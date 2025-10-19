<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id', 'name', 'description', 'order', 'estimated_hours',
        'topic_type', 'resources', 'assessment_criteria'
    ];

    protected $casts = [
        'resources' => 'array',
        'assessment_criteria' => 'array'
    ];

    const TYPE_THEORY = 'theory';
    const TYPE_PRACTICE = 'practice';
    const TYPE_PROJECT = 'project';
    const TYPE_TEST = 'test';

    public function module()
    {
        return $this->belongsTo(CurriculumModule::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function isTheory()
    {
        return $this->topic_type === self::TYPE_THEORY;
    }

    public function isPractice()
    {
        return $this->topic_type === self::TYPE_PRACTICE;
    }
}
