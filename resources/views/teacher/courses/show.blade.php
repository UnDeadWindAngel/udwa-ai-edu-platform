@extends('layouts.app')

@section('title', $course->title . ' - Панель учителя')

@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Панель учителя</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.index') }}">Мои курсы</a></li>
                    <li class="breadcrumb-item active">{{ $course->title }}</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1><i class="fas fa-graduation-cap"></i> {{ $course->title }}</h1>
                    <p class="text-muted mb-0">Управление курсом и его содержанием</p>
                </div>
                <div>
                    <a href="{{ route('teacher.courses.edit', $course) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Редактировать
                    </a>
                    <a href="{{ route('teacher.courses.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Назад
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Статистика курса -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-user-graduate fa-2x text-primary mb-2"></i>
                    <h3>{{ $course->enrollments->count() }}</h3>
                    <p class="mb-0">Студентов</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-book fa-2x text-success mb-2"></i>
                    <h3>{{ $course->lessons->count() }}</h3>
                    <p class="mb-0">Уроков</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                    <h3>{{ round($course->enrollments->avg('progress_percentage') ?? 0, 1) }}%</h3>
                    <p class="mb-0">Средний прогресс</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-star fa-2x text-warning mb-2"></i>
                    <h3>{{ round($course->enrollments->filter(function($e) { return $e->progress_percentage == 100; })->count() / max(1, $course->enrollments->count()) * 100, 1) }}%</h3>
                    <p class="mb-0">Завершили курс</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Основная информация -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-info-circle"></i> Информация о курсе</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Предмет:</strong>
                                <i class="{{ $course->curriculum->subject->icon }} me-1"
                                   style="color: {{ $course->curriculum->subject->color }}"></i>
                                {{ $course->curriculum->subject->name }}
                            </p>
                            <p><strong>Уровень:</strong>
                                <span class="badge bg-light text-dark">{{ $course->curriculum->level->name }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Статус:</strong>
                                @if(!$course->is_approved)
                                    <span class="badge bg-warning">На модерации</span>
                                @elseif($course->is_active)
                                    <span class="badge bg-success">Активен</span>
                                @else
                                    <span class="badge bg-secondary">Неактивен</span>
                                @endif
                            </p>
                            <p><strong>Дата создания:</strong> {{ $course->created_at->format('d.m.Y') }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Описание:</strong>
                        <p class="mt-2">{{ $course->description }}</p>
                    </div>

                    @if($course->curriculum->description)
                        <div class="mb-3">
                            <strong>Описание учебного плана:</strong>
                            <p class="mt-2">{{ $course->curriculum->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Управление уроками -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-book-open"></i> Уроки курса</h4>
                    @if($course->is_approved)
                        <button class="btn btn-success btn-sm" disabled>
                            <i class="fas fa-plus"></i> Добавить урок
                        </button>
                    @else
                        <span class="badge bg-warning">Добавление уроков доступно после одобрения курса</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($course->lessons->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($course->lessons->sortBy('order') as $lesson)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <span class="badge bg-secondary me-2">{{ $lesson->order }}</span>
                                                {{ $lesson->title }}
                                            </h6>
                                            <small class="text-muted">
                                                Длительность: {{ $lesson->estimated_duration }} мин •
                                                Сложность:
                                                <span class="badge
                                            @if($lesson->difficulty_level == 'easy') bg-success
                                            @elseif($lesson->difficulty_level == 'medium') bg-warning
                                            @else bg-danger @endif">
                                            {{ $lesson->difficulty_level == 'easy' ? 'Легкий' : ($lesson->difficulty_level == 'medium' ? 'Средний' : 'Сложный') }}
                                        </span>
                                            </small>
                                            @if($lesson->description)
                                                <p class="mb-1 mt-1 small">{{ Str::limit($lesson->description, 150) }}</p>
                                            @endif
                                        </div>
                                        <div class="ms-3">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" disabled>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h5>Пока нет уроков</h5>
                            <p class="text-muted">
                                @if($course->is_approved)
                                    Добавьте первый урок к вашему курсу
                                @else
                                    Уроки можно добавлять после одобрения курса администратором
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Боковая панель -->
        <div class="col-md-4">
            <!-- Статус модерации -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tasks"></i> Статус курса</h5>
                </div>
                <div class="card-body">
                    <div class="status-timeline">
                        <div class="status-item {{ $course->created_at ? 'completed' : '' }}">
                            <div class="status-dot"></div>
                            <div class="status-content">
                                <strong>Курс создан</strong>
                                <small class="text-muted">{{ $course->created_at->format('d.m.Y H:i') }}</small>
                            </div>
                        </div>
                        <div class="status-item {{ $course->is_approved ? 'completed' : ($course->created_at ? 'current' : '') }}">
                            <div class="status-dot"></div>
                            <div class="status-content">
                                <strong>Модерация</strong>
                                @if($course->is_approved)
                                    <small class="text-success">Одобрен</small>
                                @else
                                    <small class="text-warning">Ожидает проверки</small>
                                @endif
                            </div>
                        </div>
                        <div class="status-item {{ $course->is_approved && $course->lessons->count() > 0 ? 'completed' : '' }}">
                            <div class="status-dot"></div>
                            <div class="status-content">
                                <strong>Добавление уроков</strong>
                                @if($course->is_approved)
                                    <small class="text-muted">{{ $course->lessons->count() }} уроков</small>
                                @else
                                    <small class="text-muted">Доступно после одобрения</small>
                                @endif
                            </div>
                        </div>
                        <div class="status-item {{ $course->is_approved && $course->is_active ? 'completed' : '' }}">
                            <div class="status-dot"></div>
                            <div class="status-content">
                                <strong>Активация</strong>
                                @if($course->is_active)
                                    <small class="text-success">Курс активен</small>
                                @else
                                    <small class="text-muted">Ожидает активации</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Действия -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> Действия</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.courses.edit', $course) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Редактировать курс
                        </a>

                        @if($course->is_approved && $course->lessons->count() > 0)
                            <button class="btn btn-outline-primary" disabled>
                                <i class="fas fa-chart-bar"></i> Статистика курса
                            </button>
                            <button class="btn btn-outline-info" disabled>
                                <i class="fas fa-comments"></i> Управление обсуждениями
                            </button>
                        @endif

                        @if($course->is_approved && $course->is_active)
                            <button class="btn btn-outline-success" disabled>
                                <i class="fas fa-eye"></i> Предпросмотр для студентов
                            </button>
                        @endif

                        <form action="{{ route('teacher.courses.destroy', $course) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger"
                                    onclick="return confirm('Вы уверены, что хотите удалить этот курс? Это действие нельзя отменить.')">
                                <i class="fas fa-trash"></i> Удалить курс
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Информация о учебном плане -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Учебный план</h5>
                </div>
                <div class="card-body">
                    <p><strong>Общее время:</strong> {{ $course->curriculum->total_hours }} часов</p>
                    <p><strong>Теория:</strong> {{ $course->curriculum->theory_hours }} часов</p>
                    <p><strong>Практика:</strong> {{ $course->curriculum->practice_hours }} часов</p>

                    @if($course->curriculum->weekly_schedule)
                        <div class="mt-3">
                            <strong>Расписание:</strong>
                            <ul class="small mb-0">
                                <li>Недель: {{ $course->curriculum->weekly_schedule['weeks'] ?? 'N/A' }}</li>
                                <li>Часов в неделю: {{ $course->curriculum->weekly_schedule['hours_per_week'] ?? 'N/A' }}</li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .status-timeline {
            position: relative;
            padding-left: 20px;
        }
        .status-item {
            position: relative;
            padding: 8px 0;
            margin-bottom: 10px;
        }
        .status-dot {
            position: absolute;
            left: -20px;
            top: 12px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #dee2e6;
            border: 2px solid white;
        }
        .status-item.completed .status-dot {
            background-color: #28a745;
        }
        .status-item.current .status-dot {
            background-color: #007bff;
            animation: pulse 1.5s infinite;
        }
        .status-content {
            margin-left: 10px;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
    </style>
@endpush
