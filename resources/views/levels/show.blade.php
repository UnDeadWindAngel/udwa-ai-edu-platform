@extends('layouts.app')

@section('title', $subject->name . ' - ' . $level->name)

@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Предметы</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('subjects.show', $subject) }}">{{ $subject->name }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('subjects.levels', $subject) }}">Уровни</a></li>
                    <li class="breadcrumb-item active">{{ $level->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Заголовок и прогресс -->
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1>
                        <i class="{{ $subject->icon }} me-2" style="color: {{ $subject->color }}"></i>
                        {{ $subject->name }} - {{ $level->name }}
                    </h1>
                    <p class="text-muted mb-0">{{ $level->description }}</p>
                </div>
                <div class="text-end">
                    <div class="progress mb-2" style="width: 200px; height: 20px;">
                        <div class="progress-bar bg-success"
                             style="width: {{ $progress->progress_percentage }}%">
                            {{ $progress->progress_percentage }}%
                        </div>
                    </div>
                    <small class="text-muted">Ваш прогресс</small>
                </div>
            </div>
        </div>
    </div>

    @if($curriculum)
        <div class="row">
            <!-- Содержание учебного плана -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-clipboard-list"></i> Содержание обучения</h4>
                    </div>
                    <div class="card-body">
                        <div class="curriculum-content">
                            @foreach($curriculum->modules as $module)
                                <div class="module-card mb-4">
                                    <div class="module-header bg-light p-3 rounded">
                                        <h5 class="mb-2">
                                            <i class="fas fa-folder-open text-primary me-2"></i>
                                            Модуль {{ $loop->iteration }}: {{ $module->name }}
                                        </h5>
                                        <p class="text-muted mb-2">{{ $module->description }}</p>
                                        <div class="module-meta">
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> {{ $module->total_hours }} часов
                                                (Теория: {{ $module->theory_hours }}ч, Практика: {{ $module->practice_hours }}ч)
                                            </small>
                                        </div>
                                    </div>

                                    <div class="module-body mt-3">
                                        @if($module->topics->count() > 0)
                                            <div class="topics-list">
                                                @foreach($module->topics as $topic)
                                                    <div class="topic-item card mb-2">
                                                        <div class="card-body py-3">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mb-1">
                                                        <span class="badge bg-secondary me-2">
                                                            {{ $loop->parent->iteration }}.{{ $loop->iteration }}
                                                        </span>
                                                                        {{ $topic->name }}
                                                                    </h6>
                                                                    @if($topic->description)
                                                                        <p class="text-muted small mb-1">{{ $topic->description }}</p>
                                                                    @endif
                                                                    <div class="topic-meta">
                                                                        <small class="text-muted me-3">
                                                                            <i class="fas fa-clock"></i> {{ $topic->estimated_hours }} часов
                                                                        </small>
                                                                        <span class="badge
                                                            @if($topic->topic_type == 'theory') bg-info
                                                            @elseif($topic->topic_type == 'practice') bg-success
                                                            @elseif($topic->topic_type == 'project') bg-warning
                                                            @else bg-secondary @endif">
                                                            @if($topic->topic_type == 'theory') Теория
                                                                            @elseif($topic->topic_type == 'practice') Практика
                                                                            @elseif($topic->topic_type == 'project') Проект
                                                                            @else Тестирование
                                                                            @endif
                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3">
                                                                    <button class="btn btn-outline-primary btn-sm" disabled>
                                                                        <i class="fas fa-play"></i> Начать
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-3">
                                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-0">Темы для этого модуля пока не добавлены</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Ресурсы для обучения -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-download"></i> Дополнительные ресурсы</h4>
                    </div>
                    <div class="card-body">
                        <div class="resources-list">
                            <div class="resource-item d-flex justify-content-between align-items-center p-3 border rounded mb-2">
                                <div>
                                    <h6 class="mb-1">Учебное пособие по {{ $subject->name }}</h6>
                                    <small class="text-muted">PDF документ • 2.4 MB</small>
                                </div>
                                <button class="btn btn-outline-primary btn-sm" disabled>
                                    <i class="fas fa-download"></i> Скачать
                                </button>
                            </div>

                            <div class="resource-item d-flex justify-content-between align-items-center p-3 border rounded mb-2">
                                <div>
                                    <h6 class="mb-1">Практические задания</h6>
                                    <small class="text-muted">ZIP архив • 1.1 MB</small>
                                </div>
                                <button class="btn btn-outline-primary btn-sm" disabled>
                                    <i class="fas fa-download"></i> Скачать
                                </button>
                            </div>

                            <div class="resource-item d-flex justify-content-between align-items-center p-3 border rounded">
                                <div>
                                    <h6 class="mb-1">Дополнительные материалы</h6>
                                    <small class="text-muted">Ссылки и видео ресурсы</small>
                                </div>
                                <button class="btn btn-outline-info btn-sm" disabled>
                                    <i class="fas fa-external-link-alt"></i> Открыть
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Боковая панель -->
            <div class="col-md-4">
                <!-- Прогресс и статистика -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-line"></i> Ваш прогресс</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="progress-circle mx-auto mb-3"
                                 data-progress="{{ $progress->progress_percentage }}">
                                <div class="circle-bg"></div>
                                <div class="circle-progress"></div>
                                <div class="circle-text">
                                    <span class="percentage">{{ $progress->progress_percentage }}%</span>
                                </div>
                            </div>
                            <h4>{{ $progress->progress_percentage }}% завершено</h4>
                        </div>

                        <div class="progress-stats">
                            <div class="stat-item d-flex justify-content-between py-2 border-bottom">
                                <span>Начато:</span>
                                <strong>{{ $progress->started_at ? $progress->started_at->format('d.m.Y') : 'Не начато' }}</strong>
                            </div>
                            <div class="stat-item d-flex justify-content-between py-2 border-bottom">
                                <span>Модулей изучено:</span>
                                <strong>0/{{ $curriculum->modules->count() }}</strong>
                            </div>
                            <div class="stat-item d-flex justify-content-between py-2 border-bottom">
                                <span>Тем завершено:</span>
                                <strong>0/{{ $curriculum->getTotalTopicsAttribute() }}</strong>
                            </div>
                            <div class="stat-item d-flex justify-content-between py-2">
                                <span>Общее время:</span>
                                <strong>{{ $curriculum->total_hours }} часов</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Навигация по уровням -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-compass"></i> Навигация</h5>
                    </div>
                    <div class="card-body">
                        <div class="navigation-links">
                            <a href="{{ route('subjects.levels', $subject) }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-list"></i> Все уровни предмета
                            </a>

                            @if($level->prerequisite)
                                <a href="{{ route('levels.show', [$subject, $level->prerequisite]) }}"
                                   class="btn btn-outline-secondary w-100 mb-2">
                                    <i class="fas fa-arrow-left"></i> Предыдущий уровень
                                </a>
                            @endif

                            @php
                                $nextLevel = $level->nextLevels->first();
                            @endphp
                            @if($nextLevel && $progress->status === 'completed')
                                <a href="{{ route('levels.show', [$subject, $nextLevel]) }}"
                                   class="btn btn-outline-success w-100 mb-2">
                                    <i class="fas fa-arrow-right"></i> Следующий уровень
                                </a>
                            @elseif($nextLevel)
                                <button class="btn btn-outline-secondary w-100 mb-2" disabled>
                                    <i class="fas fa-lock"></i> Следующий уровень
                                </button>
                            @endif

                            <a href="{{ route('subjects.show', $subject) }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-home"></i> На страницу предмета
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Цели обучения -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bullseye"></i> Цели обучения</h5>
                    </div>
                    <div class="card-body">
                        @if($level->learning_outcomes && count($level->learning_outcomes) > 0)
                            <ul class="list-unstyled mb-0">
                                @foreach($level->learning_outcomes as $outcome)
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <small>{{ $outcome }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted small mb-0">Цели обучения пока не указаны</p>
                        @endif
                    </div>
                </div>

                <!-- Помощь и поддержка -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-life-ring"></i> Нужна помощь?</h5>
                    </div>
                    <div class="card-body">
                        <div class="support-links">
                            <button class="btn btn-outline-primary w-100 mb-2" disabled>
                                <i class="fas fa-question-circle"></i> Задать вопрос
                            </button>
                            <button class="btn btn-outline-success w-100 mb-2" disabled>
                                <i class="fas fa-comments"></i> Обсуждение с группой
                            </button>
                            <button class="btn btn-outline-info w-100" disabled>
                                <i class="fas fa-book"></i> Дополнительные материалы
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                        <h4>Учебный план не найден</h4>
                        <p class="text-muted">Для этого уровня пока не создан учебный план. Пожалуйста, обратитесь к администратору.</p>
                        <a href="{{ route('subjects.levels', $subject) }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Вернуться к уровням
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .progress-circle {
            position: relative;
            width: 120px;
            height: 120px;
        }

        .circle-bg {
            fill: none;
            stroke: #e6e6e6;
            stroke-width: 3.8;
        }

        .circle-progress {
            fill: none;
            stroke: #28a745;
            stroke-width: 3.8;
            stroke-linecap: round;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
            transition: stroke-dasharray 0.3s ease;
        }

        .circle-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .percentage {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
        }

        .module-card {
            transition: all 0.3s ease;
        }

        .module-card:hover {
            transform: translateY(-2px);
        }

        .topic-item {
            transition: all 0.3s ease;
        }

        .topic-item:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Инициализация кругового прогресса
            const progressCircle = document.querySelector('.progress-circle');
            if (progressCircle) {
                const progress = parseInt(progressCircle.getAttribute('data-progress'));
                const radius = 54;
                const circumference = 2 * Math.PI * radius;
                const offset = circumference - (progress / 100) * circumference;

                const circleProgress = progressCircle.querySelector('.circle-progress');
                circleProgress.style.strokeDasharray = `${circumference} ${circumference}`;
                circleProgress.style.strokeDashoffset = offset;
            }
        });
    </script>
@endpush
