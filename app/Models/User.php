<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'middle_name',
        'birth_date',
        'phone',
        'registration_address',
        'residential_address',
        'same_address',
        'avatar',
        'bio',
        'is_active',
        'last_login_at',
        'timezone',
        'language',
        'student_id',
        'grade',
        'emergency_contacts'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'birth_date' => 'date',
        'same_address' => 'boolean',
        'emergency_contacts' => 'array'
    ];

    // Отношения
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function levelProgress()
    {
        return $this->hasMany(LevelProgress::class, 'student_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function studentProgress()
    {
        return $this->hasMany(StudentProgress::class, 'student_id');
    }

    // Родительские отношения
    public function children()
    {
        return $this->belongsToMany(User::class, 'parent_student_relationships', 'parent_id', 'student_id')
            ->withPivot('relationship_type', 'is_primary', 'can_view_progress', 'can_receive_notifications')
            ->withTimestamps();
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'parent_student_relationships', 'student_id', 'parent_id')
            ->withPivot('relationship_type', 'is_primary', 'can_view_progress', 'can_receive_notifications')
            ->withTimestamps();
    }

    // Созданные курсы (для учителей)
    public function createdCourses()
    {
        return $this->hasMany(Course::class, 'created_by');
    }

    // Загруженные медиафайлы
    public function uploadedMedia()
    {
        return $this->hasMany(MediaLibrary::class, 'uploaded_by');
    }

    // Методы для атрибутов
    public function getFullNameAttribute()
    {
        return trim("{$this->last_name} {$this->first_name} {$this->middle_name}");
    }

    public function getShortNameAttribute()
    {
        $lastName = $this->last_name ?? '';
        $firstName = $this->first_name ? mb_substr($this->first_name, 0, 1) . '.' : '';
        $middleName = $this->middle_name ? mb_substr($this->middle_name, 0, 1) . '.' : '';

        return trim("{$lastName} {$firstName}{$middleName}");
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    // Проверка ролей
    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }

    public function isAdmin() { return $this->hasRole(Role::ADMIN); }
    public function isTeacher() { return $this->hasRole(Role::TEACHER); }
    public function isModerator() { return $this->hasRole(Role::MODERATOR); }
    public function isStudent() { return $this->hasRole(Role::STUDENT); }
    public function isParent() { return $this->hasRole(Role::PARENT); }

    // Проверка разрешений
    public function canModerateContent()
    {
        return $this->isAdmin() || $this->isModerator();
    }

    public function canManageUsers()
    {
        return $this->isAdmin();
    }

    public function canViewStudentProgress($studentId)
    {
        if ($this->isAdmin() || $this->isTeacher()) {
            return true;
        }

        if ($this->isParent()) {
            return $this->children()->where('student_id', $studentId)
                ->where('can_view_progress', true)
                ->exists();
        }

        // Студент может видеть только свой прогресс
        if ($this->isStudent()) {
            return $this->id == $studentId;
        }

        return false;
    }

    // Бизнес логика
    public function addChild($studentId, $relationshipType = 'parent', $isPrimary = false)
    {
        try {
            // Проверяем, что студент существует
            $student = User::findOrFail($studentId);

            // Проверяем, что пользователь действительно студент
            if (!$student->isStudent()) {
                return false;
            }

            // Проверяем, что связь еще не существует
            if ($this->children()->where('student_id', $studentId)->exists()) {
                return false;
            }

            // Создаем связь
            $this->children()->attach($studentId, [
                'relationship_type' => $relationshipType,
                'is_primary' => $isPrimary,
                'can_view_progress' => true,
                'can_receive_notifications' => true
            ]);

            return true;

        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function getActiveEnrollments()
    {
        return $this->enrollments()->active()->with('course')->get();
    }

    public function getCompletedCourses()
    {
        return $this->enrollments()->completed()->with('course')->get();
    }

    public function getOverallProgress()
    {
        $enrollments = $this->enrollments()->active()->get();
        if ($enrollments->isEmpty()) return 0;

        $totalProgress = $enrollments->avg('progress_percentage');
        return round($totalProgress, 1);
    }
}
