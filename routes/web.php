<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
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
    });

    // Маршруты для учителей
    Route::prefix('teacher')->middleware(['role:teacher'])->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
    });
});
