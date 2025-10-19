@extends('layouts.app')

@section('title', $subject->name . ' - UDWA AI Edu Platform')

@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Предметы</a></li>
                    <li class="breadcrumb-item active">{{ $subject->name }}</li>
                </ol>
            </nav>

            <div class="d-flex align-items-center mb-4">
                <i class="{{ $subject->icon }} fa-2x me-3" style="color: {{ $subject->color }}"></i>
                <div>
                    <h1 class="mb-1">{{ $subject->name }}</h1>
                    <p class="text-muted mb-0">{{ $subject->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-layer-group"></i> Уровни изучения</h4>
                </div>
                <div class="card-body">
                    @foreach($levels as $level)
                        <div class="level-item mb-4 p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h5 class="mb-2">
                                        {{ $level->name }}
                                        @if($level->status === 'completed')
                                            <span class="badge bg-success ms-2"><i class="fas fa-check"></i> Завершен</span>
                                        @elseif($level->status === 'in_progress')
                                            <span class="badge bg-primary ms-2"><i class="fas fa-spinner"></i> В процессе</span>
                                        @elseif($level->status === 'locked')
                                            <span class="badge bg-secondary ms-2"><i class="fas fa-lock"></i> Заблокирован</span>
                                        @else
                                            <span class="badge bg-light text-dark ms-2"><i class="fas fa-play"></i> Доступен</span>
                                        @endif
                                    </h5>
                                    <p class="text-muted mb-2">{{ $level->description }}</p>
                                    <div class="d-flex text-muted small">
                                        <span class="me-3"><i class="fas fa-clock"></i> {{ $level->min_hours }}-{{ $level->max_hours }} часов</span>
                                        <span><i class="fas fa-trophy"></i> Требуется: {{ $level->min_score }}%</span>
                                    </div>

                                    @if($level->progress && $level->progress->progress_percentage > 0)
                                        <div class="mt-2">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar
                                        @if($level->status === 'completed') bg-success
                                        @else bg-primary @endif"
                                                     style="width: {{ $level->progress->progress_percentage }}%">
                                                </div>
                                            </div>
                                            <small class="text-muted">Прогресс: {{ $level->progress->progress_percentage }}%</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="ms-3">
                                    @if($level->status === 'completed')
                                        <span class="text-success">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                    <div class="small text-center mt-1">Оценка: {{ $level->progress->score }}</div>
                                </span>
                                    @elseif($level->status === 'in_progress')
                                        <a href="{{ route('levels.show', [$subject, $level]) }}" class="btn btn-primary">
                                            Продолжить
                                        </a>
                                    @elseif($level->status === 'not_started')
                                        <a href="{{ route('levels.start', [$subject, $level]) }}" class="btn btn-success">
                                            Начать
                                        </a>
                                    @else
                                        <button class="btn btn-secondary" disabled title="Завершите предыдущий уровень">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> О предмете</h5>
                </div>
                <div class="card-body">
                    <p><strong>Цвет:</strong> <span class="badge" style="background-color: {{ $subject->color }}">{{ $subject->color }}</span></p>
                    <p><strong>Статус:</strong>
                        @if($subject->is_active)
                            <span class="badge bg-success">Активен</span>
                        @else
                            <span class="badge bg-secondary">Неактивен</span>
                        @endif
                    </p>

                    @if(auth()->user()->isStudent())
                        @php
                            $userProgress = \App\Models\LevelProgress::where('student_id', auth()->id())
                                ->where('subject_id', $subject->id)
                                ->get();
                            $completed = $userProgress->where('status', 'completed')->count();
                            $inProgress = $userProgress->where('status', 'in_progress')->count();
                            $totalLevels = $levels->count();
                        @endphp

                        <div class="mt-4">
                            <h6>Ваш прогресс по предмету:</h6>
                            <div class="progress mb-2" style="height: 20px;">
                                <div class="progress-bar bg-success" style="width: {{ ($completed / $totalLevels) * 100 }}%">
                                    {{ $completed }}
                                </div>
                                <div class="progress-bar bg-primary" style="width: {{ ($inProgress / $totalLevels) * 100 }}%">
                                    {{ $inProgress }}
                                </div>
                            </div>
                            <div class="small text-muted">
                                <span class="text-success">● Завершено: {{ $completed }}</span> |
                                <span class="text-primary">● В процессе: {{ $inProgress }}</span> |
                                <span class="text-secondary">● Всего: {{ $totalLevels }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Система уровней</h5>
                </div>
                <div class="card-body">
                    <div class="level-progression">
                        @foreach($levels as $level)
                            <div class="level-step d-flex align-items-center mb-2">
                                <div class="level-circle
                            @if($level->status === 'completed') bg-success
                            @elseif($level->status === 'in_progress') bg-primary
                            @elseif($level->status === 'locked') bg-secondary
                            @else bg-light border @endif
                            rounded-circle d-flex align-items-center justify-content-center me-2"
                                     style="width: 30px; height: 30px;"
                                >
                                    @if($level->status === 'completed')
                                        <i class="fas fa-check text-white small"></i>
                                    @elseif($level->status === 'in_progress')
                                        <i class="fas fa-spinner text-white small"></i>
                                    @elseif($level->status === 'locked')
                                        <i class="fas fa-lock text-white small"></i>
                                    @else
                                        <span class="small">{{ $loop->iteration }}</span>
                                    @endif
                                </div>
                                <span class="flex-grow-1">{{ $level->name }}</span>
                            </div>
                            @if(!$loop->last)
                                <div class="level-connector ms-2 mb-2" style="border-left: 2px solid #dee2e6; height: 20px; margin-left: 14px;"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .level-item {
            transition: all 0.3s ease;
        }
        .level-item:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
        }
        .level-circle {
            transition: all 0.3s ease;
        }
        .level-progression {
            position: relative;
        }
    </style>
@endpush
