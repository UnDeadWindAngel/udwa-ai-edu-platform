<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentStudentRelationship extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 'student_id', 'relationship_type', 'is_primary',
        'can_view_progress', 'can_receive_notifications', 'permissions',
        'verified_at', 'verified_by'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'can_view_progress' => 'boolean',
        'can_receive_notifications' => 'boolean',
        'permissions' => 'array',
        'verified_at' => 'datetime'
    ];

    const RELATIONSHIP_MOTHER = 'mother';
    const RELATIONSHIP_FATHER = 'father';
    const RELATIONSHIP_GUARDIAN = 'guardian';
    const RELATIONSHIP_OTHER = 'other';

    // Отношения
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopeCanViewProgress($query)
    {
        return $query->where('can_view_progress', true);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    // Методы
    public function verify($verifiedBy)
    {
        $this->update([
            'verified_at' => now(),
            'verified_by' => $verifiedBy
        ]);
    }

    public function isVerified()
    {
        return !is_null($this->verified_at);
    }

    public function getRelationshipDisplayName()
    {
        $names = [
            self::RELATIONSHIP_MOTHER => 'Мать',
            self::RELATIONSHIP_FATHER => 'Отец',
            self::RELATIONSHIP_GUARDIAN => 'Опекун',
            self::RELATIONSHIP_OTHER => 'Другое'
        ];

        return $names[$this->relationship_type] ?? $this->relationship_type;
    }
}
