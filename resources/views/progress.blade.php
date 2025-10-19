@extends('layouts.app')

@section('title', 'Мой прогресс - UDWA AI Edu Platform')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-chart-line"></i> Мой прогресс</h1>
            <p class="lead">Отслеживание ваших учебных достижений</p>
        </div>
    </div>

    <!-- Общая статистика -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h3>{{ $overallProgress['completed_levels'] }}</h3>
                    <p class="mb-0">Завершено уровней</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-spinner fa-2x text-warning mb-2"></i>
                    <h3>{{ $overallProgress['in_progress'] }}</h3>
                    <p class="mb-0">В процессе</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-chart-line fa-2x text-primary mb-2"></i>
                    <h3>{{ $overallProgress['total_progress'] }}%</h3>
                    <p class="mb-0">Общий прогресс</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-star fa-2x text-info mb-2"></i>
                    <h3>{{ $overallProgress['average_score'] }}</h3>
                    <p class="mb-0">Средний балл</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Детальный прогресс -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-list-alt"></i> Детальный прогресс по предметам</h4>
                </div>
                <div class="card-body">
                    @if($progress->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Предмет</th>
                                    <th>Уровень</th>
                                    <th>Статус</th>
                                    <th>Прогресс</th>
                                    <th>Оценка</th>
                                    <th>Дата начала</th>
                                    <th>Дата завершения</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($progress as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="{{ $item->subject->icon }} me-2" style="color: {{ $item->subject->color }}"></i>
                                                {{ $item->subject->name }}
                                            </div>
                                        </td>
                                        <td>{{ $item->level->name }}</td>
                                        <td>
                                        <span class="badge
                                            @if($item->status === 'completed') bg-success
                                            @elseif($item->status === 'in_progress') bg-primary
                                            @elseif($item->status === 'locked') bg-secondary
                                            @else bg-light text-dark @endif">
                                            @if($item->status === 'completed') Завершено
                                            @elseif($item->status === 'in_progress') В процессе
                                            @elseif($item->status === 'locked') Заблокирован
                                            @else Не начато
                                            @endif
                                        </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-2" style="height: 8px; width: 80px;">
                                                    <div class="progress-bar
                                                    @if($item->status === 'completed') bg-success
                                                    @else bg-primary @endif"
                                                         style="width: {{ $item->progress_percentage }}%">
                                                    </div>
                                                </div>
                                                <small>{{ $item->progress_percentage }}%</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($item->score)
                                                <span class="fw-bold
                                                @if($item->score >= 90) text-success
                                                @elseif($item->score >= 70) text-warning
                                                @else text-danger @endif">
                                                {{ $item->score }}
                                            </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->started_at)
                                                <small>{{ $item->started_at->format('d.m.Y') }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->completed_at)
                                                <small>{{ $item->completed_at->format('d.m.Y') }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status === 'in_progress')
                                                <a href="{{ route('levels.show', [$item->subject, $item->level]) }}"
                                                   class="btn btn-primary btn-sm">
                                                    Продолжить
                                                </a>
                                            @elseif($item->status === 'not_started')
                                                <a href="{{ route('levels.start', [$item->subject, $item->level]) }}"
                                                   class="btn btn-success btn-sm">
                                                    Начать
                                                </a>
                                            @elseif($item->status === 'completed')
                                                <span class="text-success">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                            <h4>Пока нет данных о прогрессе</h4>
                            <p class="text-muted">Начните изучать предметы, чтобы отслеживать свой прогресс</p>
                            <a href="{{ route('subjects.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-right"></i> Выбрать предмет
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
