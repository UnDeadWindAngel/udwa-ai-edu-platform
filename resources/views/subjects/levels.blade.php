@extends('layouts.app')

@section('title', $subject->name . ' - Уровни обучения')

@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Предметы</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('subjects.show', $subject) }}">{{ $subject->name }}</a></li>
                    <li class="breadcrumb-item active">Уровни обучения</li>
                </ol>
            </nav>

            <div class="d-flex align-items-center mb-4">
                <i class="{{ $subject->icon }} fa-2x me-3" style="color: {{ $subject->color }}"></i>
                <div>
                    <h1 class="mb-1">{{ $subject->name }} - Уровни обучения</h1>
                    <p class="text-muted mb-0">Последовательное изучение предмета от основ к углубленным знаниям</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Прогресс студента -->
    @if(auth()->user()->isStudent())
        @php
            $user = auth()->user();
            $completedLevels = $levels->where('status', 'completed')->count();
            $totalLevels = $levels->count();
            $overallProgress = $totalLevels > 0 ? ($completedLevels / $totalLevels) * 100 : 0;
        @endphp
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-2">Ваш прогресс по предмету</h5>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" style="width: {{ $overallProgress }}%">
                                        {{ $completedLevels }}/{{ $totalLevels }}
                                    </div>
                                </div>
                                <small class="text-muted">
                                    Завершено {{ $completedLevels }} из {{ $totalLevels }} уровней ({{ round($overallProgress, 1) }}%)
                                </small>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="fw-bold text-success">{{ $completedLevels }}</div>
                                        <small class="text-muted">Завершено</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="fw-bold text-primary">{{ $levels->where('status', 'in_progress')->count() }}</div>
                                        <small class="text-muted">В процессе</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Система уровней -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-layer-group"></i> Система уровней обучения</h4>
                </div>
                <div class="card-body">
                    <div class="levels-container">
                        @foreach($levels as $level)
                            <div class="level-card mb-4">
                                <div class="card level-inner-card
                            @if($level->status === 'completed') border-success
                            @elseif($level->status === 'in_progress') border-primary
                            @elseif($level->status === 'locked') border-secondary
                            @else border @endif">

                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-1 text-center">
                                                <div class="level-number
                                            @if($level->status === 'completed') bg-success text-white
                                            @elseif($level->status === 'in_progress') bg-primary text-white
                                            @elseif($level->status === 'locked') bg-secondary text-white
                                            @else bg-light text-dark @endif
                                            rounded-circle d-inline-flex align-items-center justify-content-center"
                                                     style="width: 50px; height: 50px;">
                                                    @if($level->status === 'completed')
                                                        <i class="fas fa-check"></i>
                                                    @elseif($level->status === 'in_progress')
                                                        <i class="fas fa-spinner"></i>
                                                    @elseif($level->status === 'locked')
                                                        <i class="fas fa-lock"></i>
                                                    @else
                                                        {{ $loop->iteration }}
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-7">
                                                <h4 class="mb-2">{{ $level->name }}</h4>
                                                <p class="text-muted mb-2">{{ $level->description }}</p>

                                                <div class="level-meta">
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="fas fa-clock"></i> {{ $level->min_hours }}-{{ $level->max_hours }} часов
                                            </span>
                                                    <span class="badge bg-light text-dark">
                                                <i class="fas fa-trophy"></i> Требуется {{ $level->min_score }}% для перехода
                                            </span>
                                                </div>

                                                @if($level->progress && $level->progress->progress_percentage > 0)
                                                    <div class="mt-3">
                                                        <div class="d-flex justify-content-between small text-muted mb-1">
                                                            <span>Ваш прогресс</span>
                                                            <span>{{ $level->progress->progress_percentage }}%</span>
                                                        </div>
                                                        <div class="progress" style="height: 8px;">
                                                            <div class="progress-bar
                                                    @if($level->status === 'completed') bg-success
                                                    @else bg-primary @endif"
                                                                 style="width: {{ $level->progress->progress_percentage }}%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-md-4 text-center">
                                                <div class="level-actions">
                                                    @if($level->status === 'completed')
                                                        <div class="text-success mb-2">
                                                            <i class="fas fa-check-circle fa-2x"></i>
                                                            <div class="mt-1">Завершено</div>
                                                            @if($level->progress && $level->progress->score)
                                                                <div class="fw-bold">Оценка: {{ $level->progress->score }}</div>
                                                            @endif
                                                        </div>
                                                        <small class="text-muted">
                                                            Завершено: {{ $level->progress->completed_at->format('d.m.Y') }}
                                                        </small>
                                                    @elseif($level->status === 'in_progress')
                                                        <a href="{{ route('levels.show', [$subject, $level]) }}"
                                                           class="btn btn-primary btn-lg mb-2">
                                                            <i class="fas fa-play"></i> Продолжить
                                                        </a>
                                                        <div class="small text-muted">
                                                            Начато: {{ $level->progress->started_at->format('d.m.Y') }}
                                                        </div>
                                                    @elseif($level->status === 'not_started')
                                                        <a href="{{ route('levels.start', [$subject, $level]) }}"
                                                           class="btn btn-success btn-lg mb-2">
                                                            <i class="fas fa-play-circle"></i> Начать обучение
                                                        </a>
                                                        <div class="small text-muted">
                                                            Доступно для начала
                                                        </div>
                                                    @else
                                                        <button class="btn btn-secondary btn-lg mb-2" disabled>
                                                            <i class="fas fa-lock"></i> Заблокировано
                                                        </button>
                                                        <div class="small text-muted">
                                                            Завершите предыдущий уровень
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Соединительная линия между уровнями -->
                                @if(!$loop->last)
                                    <div class="level-connector text-center">
                                        <i class="fas fa-arrow-down text-muted"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Информация о системе уровней -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> О системе уровней</h5>
                </div>
                <div class="card-body">
                    <p>Система уровней построена по принципу последовательного обучения:</p>
                    <ul>
                        <li><strong>Начальный:</strong> Основные понятия и базовые навыки</li>
                        <li><strong>Базовый:</strong> Фундаментальные знания и практическое применение</li>
                        <li><strong>Продвинутый:</strong> Углубленное изучение и сложные задачи</li>
                        <li><strong>Профильный:</strong> Специализация и экспертные знания</li>
                    </ul>
                    <p class="mb-0">Каждый следующий уровень открывается после успешного завершения предыдущего.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-question-circle"></i> Как проходит обучение</h5>
                </div>
                <div class="card-body">
                    <div class="learning-steps">
                        <div class="step-item d-flex align-items-center mb-3">
                            <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 30px; height: 30px;">1</div>
                            <div>
                                <strong>Изучение теории</strong>
                                <p class="small text-muted mb-0">Интерактивные уроки и материалы</p>
                            </div>
                        </div>
                        <div class="step-item d-flex align-items-center mb-3">
                            <div class="step-number bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 30px; height: 30px;">2</div>
                            <div>
                                <strong>Практические задания</strong>
                                <p class="small text-muted mb-0">Применение знаний на практике</p>
                            </div>
                        </div>
                        <div class="step-item d-flex align-items-center">
                            <div class="step-number bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 30px; height: 30px;">3</div>
                            <div>
                                <strong>Итоговое тестирование</strong>
                                <p class="small text-muted mb-0">Проверка усвоенных знаний</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .level-card {
            position: relative;
        }
        .level-inner-card {
            transition: all 0.3s ease;
        }
        .level-inner-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .level-connector {
            margin: 10px 0;
            opacity: 0.5;
        }
        .level-number {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .learning-steps {
            position: relative;
        }
        .step-item {
            position: relative;
        }
    </style>
@endpush
