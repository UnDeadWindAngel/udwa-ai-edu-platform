@extends('layouts.app')

@section('title', 'Мои курсы - Панель учителя')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1><i class="fas fa-graduation-cap"></i> Мои курсы</h1>
                    <p class="lead">Управление вашими учебными курсами</p>
                </div>
                <div>
                    <a href="{{ route('teacher.courses.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Создать курс
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Статистика -->
    @php
        $approvedCount = $courses->where('is_approved', true)->where('is_active', true)->count();
        $pendingCount = $courses->where('is_approved', false)->count();
        $totalStudents = 0;
        foreach ($courses as $course) {
            $totalStudents += $course->enrollments_count;
        }
    @endphp

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-graduation-cap fa-2x text-primary mb-2"></i>
                    <h3>{{ $courses->count() }}</h3>
                    <p class="mb-0">Всего курсов</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h3>{{ $approvedCount }}</h3>
                    <p class="mb-0">Одобренных</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-user-graduate fa-2x text-info mb-2"></i>
                    <h3>{{ $totalStudents }}</h3>
                    <p class="mb-0">Всего студентов</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Список курсов -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Список курсов</h4>
                </div>
                <div class="card-body">
                    @if($courses->count() > 0)
                        <div class="row">
                            @foreach($courses as $course)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 course-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h5 class="card-title">{{ $course->title }}</h5>
                                                    <p class="card-text text-muted small">
                                                        {{ Str::limit($course->description, 100) }}
                                                    </p>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('teacher.courses.show', $course) }}">
                                                                <i class="fas fa-eye"></i> Просмотр
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('teacher.courses.edit', $course) }}">
                                                                <i class="fas fa-edit"></i> Редактировать
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('teacher.courses.destroy', $course) }}"
                                                                  method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                        onclick="return confirm('Удалить курс?')">
                                                                    <i class="fas fa-trash"></i> Удалить
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="course-meta mb-3">
                                                <div class="row text-center">
                                                    <div class="col-4">
                                                        <small class="text-muted">Предмет</small>
                                                        <div class="fw-bold">
                                                            <i class="{{ $course->curriculum->subject->icon }} me-1"
                                                               style="color: {{ $course->curriculum->subject->color }}"></i>
                                                            {{ $course->curriculum->subject->name }}
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <small class="text-muted">Уровень</small>
                                                        <div class="fw-bold">{{ $course->curriculum->level->name }}</div>
                                                    </div>
                                                    <div class="col-4">
                                                        <small class="text-muted">Студентов</small>
                                                        <div class="fw-bold text-primary">{{ $course->enrollments_count }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="course-status mb-3">
                                                @if(!$course->is_approved)
                                                    <span class="badge bg-warning w-100 py-2">
                                                <i class="fas fa-clock"></i> На модерации
                                            </span>
                                                @elseif($course->is_active)
                                                    <span class="badge bg-success w-100 py-2">
                                                <i class="fas fa-check"></i> Активен
                                            </span>
                                                @else
                                                    <span class="badge bg-secondary w-100 py-2">
                                                <i class="fas fa-pause"></i> Неактивен
                                            </span>
                                                @endif
                                            </div>

                                            <div class="course-progress">
                                                <div class="d-flex justify-content-between small text-muted mb-1">
                                                    <span>Прогресс студентов</span>
                                                    <span>~{{ round(($course->enrollments_count * 100) / max(1, $totalStudents)) }}%</span>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar"
                                                         style="width: {{ min(100, ($course->enrollments_count * 100) / max(1, $totalStudents)) }}%">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="text-muted">
                                                        Создан: {{ $course->created_at->format('d.m.Y') }}
                                                    </small>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <a href="{{ route('teacher.courses.show', $course) }}"
                                                       class="btn btn-sm btn-primary">
                                                        Управлять
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Пагинация -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $courses->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-graduation-cap fa-4x text-muted mb-3"></i>
                            <h4>Пока нет курсов</h4>
                            <p class="text-muted">Создайте свой первый учебный курс</p>
                            <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus"></i> Создать курс
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .course-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
    </style>
@endpush
