@extends('layouts.app')

@section('title', 'Админ панель - UDWA AI Edu Platform')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-cogs"></i> Админ панель</h1>
            <p class="lead">Управление образовательной платформой</p>
        </div>
    </div>

    <!-- Статистика -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                    <h3>{{ $stats['total_users'] }}</h3>
                    <p class="mb-0">Всего пользователей</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-chalkboard-teacher fa-2x text-success mb-2"></i>
                    <h3>{{ $stats['total_teachers'] }}</h3>
                    <p class="mb-0">Учителей</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-user-graduate fa-2x text-info mb-2"></i>
                    <h3>{{ $stats['total_students'] }}</h3>
                    <p class="mb-0">Студентов</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-user-friends fa-2x text-warning mb-2"></i>
                    <h3>{{ $stats['total_parents'] }}</h3>
                    <p class="mb-0">Родителей</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-book fa-2x text-secondary mb-2"></i>
                    <h3>{{ $stats['total_subjects'] }}</h3>
                    <p class="mb-0">Предметов</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-graduation-cap fa-2x text-success mb-2"></i>
                    <h3>{{ $stats['total_courses'] }}</h3>
                    <p class="mb-0">Всего курсов</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-play-circle fa-2x text-primary mb-2"></i>
                    <h3>{{ $stats['active_courses'] }}</h3>
                    <p class="mb-0">Активных курсов</p>
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
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-users"></i> Управление пользователями
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.content.courses') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-graduation-cap"></i> Модерация курсов
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.content.subjects') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-book"></i> Управление предметами
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="#" class="btn btn-outline-warning w-100">
                                <i class="fas fa-chart-bar"></i> Аналитика
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Последние активные студенты -->
    @if($recentStudents->count() > 0)
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-user-clock"></i> Недавно активные студенты</h4>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($recentStudents as $student)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $student->full_name }}</h6>
                                        <small class="text-muted">
                                            @if($student->grade)
                                                {{ $student->grade }} •
                                            @endif
                                            Последний вход:
                                            @if($student->last_login_at)
                                                {{ $student->last_login_at->diffForHumans() }}
                                            @else
                                                Никогда
                                            @endif
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        @php
                                            $completed = $student->levelProgress->where('status', 'completed')->count();
                                            $inProgress = $student->levelProgress->where('status', 'in_progress')->count();
                                        @endphp
                                        <span class="badge bg-success">{{ $completed }}</span>
                                        <span class="badge bg-primary">{{ $inProgress }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-info-circle"></i> Системная информация</h4>
                    </div>
                    <div class="card-body">
                        <div class="system-info">
                            <p><strong>Версия Laravel:</strong> {{ app()->version() }}</p>
                            <p><strong>Версия PHP:</strong> {{ PHP_VERSION }}</p>
                            <p><strong>Окружение:</strong> {{ app()->environment() }}</p>
                            <p><strong>Время сервера:</strong> {{ now()->format('d.m.Y H:i:s') }}</p>
                            <p><strong>База данных:</strong> {{ config('database.default') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
