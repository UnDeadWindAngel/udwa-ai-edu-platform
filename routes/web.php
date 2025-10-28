<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController as AdminUserManagementController;
use App\Http\Controllers\Admin\ContentManagementController as AdminContentManagementController;
use App\Http\Controllers\Admin\ParentRelationshipController as AdminParentRelationshipController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\CourseController as TeacherCourseController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Главные маршруты
Route::get('/', [HomeController::class, 'index'])->name('home');

// Аутентификация (генерируется Laravel UI)
Auth::routes();

// Защищенные маршруты
Route::middleware(['auth'])->group(function () {

    // Профиль пользователя
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Предметы
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
    Route::get('/subjects/{subject}/levels', [SubjectController::class, 'levels'])->name('subjects.levels');

    // Уровни
    Route::get('/subjects/{subject}/levels/{level}', [LevelController::class, 'show'])->name('levels.show');
    Route::post('/subjects/{subject}/levels/{level}/start', [LevelController::class, 'start'])->name('levels.start');

    // Курсы
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

    // Прогресс
    Route::get('/progress', [HomeController::class, 'progress'])->name('progress');

    // Маршруты для родителей
    Route::prefix('parent')->middleware(['role:parent'])->group(function () {
        Route::get('/dashboard', [ParentController::class, 'dashboard'])->name('parent.dashboard');
        Route::get('/child/{childId}/progress', [ParentController::class, 'childProgress'])->name('parent.child-progress');
        Route::post('/add-child', [ParentController::class, 'addChild'])->name('parent.add-child');
    });

    // Админ-маршруты
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        // Управление пользователями
        Route::get('/users', [AdminUserManagementController::class, 'index'])->name('admin.users.index');
        Route::get('/users/{user}', [AdminUserManagementController::class, 'show'])->name('admin.users.show');
        Route::get('/users/{user}/edit', [AdminUserManagementController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [AdminUserManagementController::class, 'update'])->name('admin.users.update');
        Route::get('/users/create', [AdminUserManagementController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [AdminUserManagementController::class, 'store'])->name('admin.users.store');
        Route::delete('/users/{user}', [AdminUserManagementController::class, 'destroy'])->name('admin.users.destroy');
        Route::patch('/users/{user}/activate', [AdminUserManagementController::class, 'activate'])->name('admin.users.activate');
        Route::patch('/users/{user}/deactivate', [AdminUserManagementController::class, 'deactivate'])->name('admin.users.deactivate');
        Route::patch('/users/{user}/toggle-status', [AdminUserManagementController::class, 'toggleStatus'])->name('admin.users.toggle-status');
        Route::put('/users/{user}/roles', [AdminUserManagementController::class, 'updateRoles'])->name('admin.users.update-roles');
        Route::get('/users/{user}/roles/edit', [AdminUserManagementController::class, 'editRoles'])->name('admin.users.edit-roles');
        Route::get('/users/search', [AdminUserManagementController::class, 'search'])->name('admin.users.search');
        Route::get('/users/search-parents', [AdminUserManagementController::class, 'searchParents'])->name('admin.users.search-parents');
        Route::get('/parent-relationships', [AdminParentRelationshipController::class, 'index'])->name('admin.parent-relationships.index');
        Route::post('/parent-relationships', [AdminParentRelationshipController::class, 'store'])->name('admin.parent-relationships.store');
        Route::put('/parent-relationships/{relationship}', [AdminParentRelationshipController::class, 'update'])->name('admin.parent-relationships.update');
        Route::delete('/parent-relationships/{relationship}', [AdminParentRelationshipController::class, 'destroy'])->name('admin.parent-relationships.destroy');
        // Управление курсами
        Route::get('/content/courses', [AdminContentManagementController::class, 'courses'])->name('admin.content.courses');
        Route::patch('/courses/{course}/approve', [AdminContentManagementController::class, 'approveCourse'])->name('admin.courses.approve');
        Route::patch('/courses/{course}/reject', [AdminContentManagementController::class, 'rejectCourse'])->name('admin.courses.reject');
        Route::patch('/courses/{course}/activate', [AdminContentManagementController::class, 'activateCourse'])->name('admin.courses.activate');
        Route::patch('/courses/{course}/deactivate', [AdminContentManagementController::class, 'deactivateCourse'])->name('admin.courses.deactivate');
        // Управление предметами
        Route::get('/content/subjects', [AdminContentManagementController::class, 'subjects'])->name('admin.content.subjects');
        Route::post('/content/subjects', [AdminContentManagementController::class, 'storeSubject'])->name('admin.content.subjects.store');
        Route::put('/content/subjects/{subject}', [AdminContentManagementController::class, 'updateSubject'])->name('admin.content.subjects.update');
        Route::patch('/content/subjects/{subject}/activate', [AdminContentManagementController::class, 'activateSubject'])->name('admin.content.subjects.activate');
        Route::patch('/content/subjects/{subject}/deactivate', [AdminContentManagementController::class, 'deactivateSubject'])->name('admin.content.subjects.deactivate');
        // Управление ролями системы
        Route::get('/roles', [AdminUserManagementController::class, 'rolesIndex'])->name('admin.roles.index');
        Route::get('/roles/create', [AdminUserManagementController::class, 'rolesCreate'])->name('admin.roles.create');
        Route::post('/roles', [AdminUserManagementController::class, 'rolesStore'])->name('admin.roles.store');
        Route::get('/roles/{role}/edit', [AdminUserManagementController::class, 'rolesEdit'])->name('admin.roles.edit');
        Route::put('/roles/{role}', [AdminUserManagementController::class, 'rolesUpdate'])->name('admin.roles.update');
        Route::delete('/roles/{role}', [AdminUserManagementController::class, 'rolesDestroy'])->name('admin.roles.destroy');
    });

    // Маршруты для учителей
    Route::prefix('teacher')->middleware(['role:teacher'])->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
        Route::get('/courses', [TeacherCourseController::class, 'index'])->name('teacher.courses.index');
        Route::get('/courses/create', [TeacherCourseController::class, 'create'])->name('teacher.courses.create');
        Route::get('/courses/{course}', [TeacherCourseController::class, 'show'])->name('teacher.courses.show');
        Route::get('/courses/{course}/edit', [TeacherCourseController::class, 'edit'])->name('teacher.courses.edit');
        Route::get('/students', [TeacherDashboardController::class, 'students'])->name('teacher.students');
    });
});
