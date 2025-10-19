@extends('layouts.app')

@section('title', 'Успеваемость ' . $child->full_name)

@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Панель родителя</a></li>
                    <li class="breadcrumb-item active">{{ $child->full_name }}</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1><i class="fas fa-user-graduate"></i> {{ $child->full_name }}</h1>
                    <p class="text-muted mb-0">Детальная успеваемость студента</p>
                </div>
                <div class="text-end">
                    @if($child->grade)
                        <span class="badge bg-info fs-6">{{ $child->grade }}</span>
                    @endif
                    <span class="badge bg-primary fs-6">Студент</span>
                </div>
            </div>
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

    <!-- Детальный прогресс по предметам -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-book"></i> Прогресс по предметам</h4>
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
                                            <div class="progress" style="height: 8px; width: 100px;">
                                                <div class="progress-bar
                                                @if($item->status === 'completed') bg-success
                                                @else bg-primary @endif"
                                                     style="width: {{ $item->progress_percentage }}%">
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $item->progress_percentage }}%</small>
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
                                                {{ $item->started_at->format('d.m.Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->completed_at)
                                                {{ $item->completed_at->format('d.m.Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h5>Пока нет данных о прогрессе</h5>
                            <p class="text-muted">Студент еще не начал обучение</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- График успеваемости (простой) -->
    @if($progress->where('score', '!=', null)->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-chart-bar"></i> Динамика успеваемости</h4>
                    </div>
                    <div class="card-body">
                        <div class="simple-chart">
                            @php
                                $scores = $progress->where('score', '!=', null)
                                    ->sortBy('completed_at')
                                    ->take(10);
                            @endphp

                            <div class="chart-bars d-flex align-items-end" style="height: 200px; gap: 10px;">
                                @foreach($scores as $item)
                                    <div class="chart-bar-container text-center" style="flex: 1;">
                                        <div class="chart-bar
                                @if($item->score >= 90) bg-success
                                @elseif($item->score >= 70) bg-warning
                                @else bg-danger @endif"
                                             style="height: {{ $item->score }}%; margin: 0 auto; width: 30px;"
                                             title="{{ $item->subject->name }} - {{ $item->score }}%">
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            {{ $item->subject->name }}
                                        </small>
                                        <small class="fw-bold d-block">
                                            {{ $item->score }}
                                        </small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .chart-bar {
            transition: all 0.3s ease;
            border-radius: 4px 4px 0 0;
        }
        .chart-bar:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }
        .simple-chart {
            position: relative;
        }
    </style>
@endpush
