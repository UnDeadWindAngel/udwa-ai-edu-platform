<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelPrerequisite extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_id', 'required_level_id', 'min_score',
        'require_final_exam', 'additional_requirements'
    ];

    protected $casts = [
        'require_final_exam' => 'boolean',
        'additional_requirements' => 'array'
    ];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function requiredLevel()
    {
        return $this->belongsTo(Level::class, 'required_level_id');
    }
}
