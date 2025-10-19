@extends('layouts.app')

@section('title', 'Панель родителя - UDWA AI Edu Platform')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-user-friends"></i> Панель родителя</h1>
            <p class="lead">Отслеживание успеваемости ваших детей</p>
        </div>
    </div>

    @if($children->count() > 0)
        <div class="row">
            @foreach($children as $child)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title">{{ $child->full_name }}</h5>
                                    <p class="card-text">
                                        @if($child->grade)
                                            <span class="badge bg-info">{{ $child->grade }}</span>
                                        @endif
                                        <span class="badge bg-primary">Студент</span>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <a href="{{ route('parent.child-progress', $child->id) }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-chart-line"></i> Детальная успеваемость
                                    </a>
                                </div>
                            </div>

                            <!-- Краткая статистика -->
                            @php
                                $completed = $child->levelProgress->where('status', 'completed')->count();
                                $inProgress = $child->levelProgress->where('status', 'in_progress')->count();
                                $averageScore = $child->levelProgress->avg('score');
                                $totalProgress = $child->levelProgress->where('status', 'completed')->avg('progress_percentage');
                            @endphp

                            <div class="row text-center mb-3">
                                <div class="col-3">
                                    <div class="border rounded p-2">
                                        <div class="fw-bold text-success">{{ $completed }}</div>
                                        <small class="text-muted">Завершено</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="border rounded p-2">
                                        <div class="fw-bold text-warning">{{ $inProgress }}</div>
                                        <small class="text-muted">В процессе</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="border rounded p-2">
                                        <div class="fw-bold text-primary">{{ round($averageScore, 1) }}</div>
                                        <small class="text-muted">Средний балл</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="border rounded p-2">
                                        <div class="fw-bold text-info">{{ round($totalProgress, 1) }}%</div>
                                        <small class="text-muted">Прогресс</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Последние активности -->
                            <h6 class="mb-2">Последние активности:</h6>
                            @if($child->levelProgress->count() > 0)
                                <div class="activity-list">
                                    @foreach($child->levelProgress->sortByDesc('updated_at')->take(3) as $progress)
                                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <div>
                                                <small class="fw-bold">{{ $progress->subject->name }}</small>
                                                <br>
                                                <small class="text-muted">{{ $progress->level->name }}</small>
                                            </div>
                                            <div class="text-end">
                                <span class="badge
                                    @if($progress->status === 'completed') bg-success
                                    @elseif($progress->status === 'in_progress') bg-primary
                                    @else bg-secondary @endif">
                                    {{ $progress->status === 'completed' ? 'Завершено' : ($progress->status === 'in_progress' ? 'В процессе' : 'Не начато') }}
                                </span>
                                                @if($progress->score)
                                                    <br>
                                                    <small class="text-muted">Оценка: {{ $progress->score }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted small">Пока нет активностей</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle"></i> У вас пока нет привязанных учеников</h5>
                    <p>Чтобы просматривать успеваемость, добавьте ученика по его email.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addChildModal">
                        <i class="fas fa-plus"></i> Добавить ученика
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Модальное окно добавления ученика -->
    <div class="modal fade" id="addChildModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Добавить ученика</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('parent.add-child') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="student_email" class="form-label">Email ученика *</label>
                            <input type="email" class="form-control" id="student_email" name="student_email" required>
                            <div class="form-text">Введите email, который ученик использует для входа в систему</div>
                        </div>
                        <div class="mb-3">
                            <label for="relationship_type" class="form-label">Ваше отношение *</label>
                            <select class="form-select" id="relationship_type" name="relationship_type" required>
                                <option value="mother">Мать</option>
                                <option value="father">Отец</option>
                                <option value="guardian">Опекун</option>
                                <option value="other">Другое</option>
                            </select>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_primary" name="is_primary" value="1">
                            <label class="form-check-label" for="is_primary">Основной родитель</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
