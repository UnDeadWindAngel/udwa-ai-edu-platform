@extends('layouts.app')

@section('title', 'Панель учителя - UDWA AI Edu Platform')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-chalkboard-teacher"></i> Панель учителя</h1>
            <p class="lead">Управление вашими курсами и студентами</p>
        </div>
    </div>

    <!-- Статистика учителя -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-graduation-cap fa-2x text-primary mb-2"></i>
                    <h3>{{ $stats['total_courses'] }}</h3>
                    <p class="mb-0">Всего курсов</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-play-circle fa-2x text-success mb-2"></i>
                    <h3>{{ $stats['active_courses'] }}</h3>
                    <p class="mb-0">Активных курсов</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-user-graduate fa-2x text-info mb-2"></i>
                    <h3>{{ $stats['total_students'] }}</h3>
                    <p class="mb-0">Всего студентов</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                    <h3>{{ $stats['pending_approvals'] }}</h3>
                    <p class="mb-0">На модерации</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Быстрые действия -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-bolt"></i> Быстрые действия</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('teacher.courses.create') }}" class="btn btn-success w-100">
                                <i class="fas fa-plus"></i> Создать курс
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('teacher.courses.index') }}" class="btn btn-primary w-100">
                                <i class="fas fa-list"></i> Мои курсы
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('teacher.students') }}" class="btn btn-info w-100">
                                <i class="fas fa-users"></i> Мои студенты
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="#" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-chart-bar"></i> Аналитика
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Недавние курсы -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-clock"></i> Недавние курсы</h4>
                    <a href="{{ route('teacher.courses.index') }}" class="btn btn-sm btn-outline-primary">Все курсы</a>
                </div>
                <div class="card-body">
                    @if($recentCourses->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentCourses as $course)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $course->title }}</h6>
                                            <small class="text-muted">
                                                Создан: {{ $course->created_at->format('d.m.Y') }} •
                                                Студентов: {{ $course->enrollments_count }}
                                            </small>
                                            <div class="mt-1">
                                                @if($course->is_approved && $course->is_active)
                                                    <span class="badge bg-success">Активен</span>
                                                @elseif($course->is_approved && !$course->is_active)
                                                    <span class="badge bg-secondary">Неактивен</span>
                                                @else
                                                    <span class="badge bg-warning">На модерации</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            <a href="{{ route('teacher.courses.show', $course) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                            <h5>Пока нет курсов</h5>
                            <p class="text-muted">Создайте свой первый курс</p>
                            <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary">
                                Создать курс
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Популярные курсы -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-fire"></i> Популярные курсы</h4>
                    <span class="badge bg-primary">По количеству студентов</span>
                </div>
                <div class="card-body">
                    @if($popularCourses->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($popularCourses as $course)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $course->title }}</h6>
                                            <small class="text-muted">
                                                Студентов: {{ $course->enrollments_count }}
                                            </small>
                                            <div class="progress mt-1" style="height: 6px;">
                                                <div class="progress-bar" style="width: {{ min(100, ($course->enrollments_count / max(1, $stats['total_students'])) * 100) }}%"></div>
                                            </div>
                                        </div>
                                        <div class="ms-3 text-end">
                                            <div class="fw-bold text-primary">{{ $course->enrollments_count }}</div>
                                            <small class="text-muted">студентов</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5>Нет данных</h5>
                            <p class="text-muted">Пока нет студентов на курсах</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Уведомления и задачи -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-bell"></i> Ближайшие задачи</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <h5>Проверка работ</h5>
                                    <p class="text-muted">Работ на проверке: 0</p>
                                    <button class="btn btn-sm btn-outline-success" disabled>Перейти</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-comments fa-2x text-primary mb-2"></i>
                                    <h5>Вопросы студентов</h5>
                                    <p class="text-muted">Новых вопросов: 0</p>
                                    <button class="btn btn-sm btn-outline-primary" disabled>Перейти</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                                    <h5>Статистика</h5>
                                    <p class="text-muted">Обновлено сегодня</p>
                                    <button class="btn btn-sm btn-outline-info" disabled>Смотреть</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
