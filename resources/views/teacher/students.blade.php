@extends('layouts.app')

@section('title', 'Мои студенты - Панель учителя')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1><i class="fas fa-user-graduate"></i> Мои студенты</h1>
                    <p class="lead">Управление студентами и отслеживание их прогресса</p>
                </div>
                <div>
                    <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Назад
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Статистика -->
    @php
        $totalStudents = $students->count();
        $activeStudents = $students->filter(function($studentGroup) {
            return $studentGroup->first()->pivot->status == 'active';
        })->count();
        $completedStudents = $students->filter(function($studentGroup) {
            return $studentGroup->first()->pivot->status == 'completed';
        })->count();
    @endphp

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                    <h3>{{ $totalStudents }}</h3>
                    <p class="mb-0">Всего студентов</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-spinner fa-2x text-warning mb-2"></i>
                    <h3>{{ $activeStudents }}</h3>
                    <p class="mb-0">Активно обучаются</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h3>{{ $completedStudents }}</h3>
                    <p class="mb-0">Завершили курсы</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Список студентов -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Список студентов</h4>
                </div>
                <div class="card-body">
                    @if($students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Студент</th>
                                    <th>Курсы</th>
                                    <th>Статус</th>
                                    <th>Прогресс</th>
                                    <th>Средний балл</th>
                                    <th>Последняя активность</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($students as $studentId => $studentEnrollments)
                                    @php
                                        $student = $studentEnrollments->first()->student;
                                        $totalProgress = $studentEnrollments->avg('progress_percentage');
                                        $activeCourses = $studentEnrollments->where('pivot.status', 'active');
                                        $completedCourses = $studentEnrollments->where('pivot.status', 'completed');
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($student->avatar)
                                                    <img src="{{ asset('storage/' . $student->avatar) }}"
                                                         alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                                         style="width: 32px; height: 32px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $student->full_name }}</div>
                                                    @if($student->grade)
                                                        <small class="text-muted">{{ $student->grade }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="course-badges">
                                                @foreach($studentEnrollments->take(3) as $enrollment)
                                                    <span class="badge bg-light text-dark mb-1"
                                                          title="{{ $enrollment->course->title }}">
                                                {{ Str::limit($enrollment->course->title, 20) }}
                                            </span>
                                                @endforeach
                                                @if($studentEnrollments->count() > 3)
                                                    <span class="badge bg-secondary">
                                                +{{ $studentEnrollments->count() - 3 }}
                                            </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                        <span class="badge
                                            @if($activeCourses->count() > 0) bg-primary
                                            @elseif($completedCourses->count() > 0) bg-success
                                            @else bg-secondary @endif">
                                            @if($activeCourses->count() > 0) Активен
                                            @elseif($completedCourses->count() > 0) Завершил
                                            @else Неактивен
                                            @endif
                                        </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-2" style="height: 8px; width: 80px;">
                                                    <div class="progress-bar
                                                    @if($totalProgress >= 80) bg-success
                                                    @elseif($totalProgress >= 50) bg-warning
                                                    @else bg-danger @endif"
                                                         style="width: {{ $totalProgress }}%">
                                                    </div>
                                                </div>
                                                <small>{{ round($totalProgress, 1) }}%</small>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $avgScore = $student->levelProgress->avg('score');
                                            @endphp
                                            @if($avgScore)
                                                <span class="fw-bold
                                                @if($avgScore >= 90) text-success
                                                @elseif($avgScore >= 70) text-warning
                                                @else text-danger @endif">
                                                {{ round($avgScore, 1) }}
                                            </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($student->last_login_at)
                                                <small>{{ $student->last_login_at->diffForHumans() }}</small>
                                            @else
                                                <small class="text-muted">Никогда</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#studentProgressModal{{ $student->id }}"
                                                        title="Прогресс студента">
                                                    <i class="fas fa-chart-line"></i>
                                                </button>
                                                <button class="btn btn-outline-info" disabled title="Написать сообщение">
                                                    <i class="fas fa-envelope"></i>
                                                </button>
                                            </div>

                                            <!-- Модальное окно прогресса студента -->
                                            <div class="modal fade" id="studentProgressModal{{ $student->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Прогресс студента: {{ $student->full_name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h6>Курсы студента:</h6>
                                                                    <div class="list-group">
                                                                        @foreach($studentEnrollments as $enrollment)
                                                                            <div class="list-group-item">
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <div>
                                                                                        <strong>{{ $enrollment->course->title }}</strong>
                                                                                        <br>
                                                                                        <small class="text-muted">
                                                                                            Прогресс: {{ $enrollment->progress_percentage }}%
                                                                                        </small>
                                                                                    </div>
                                                                                    <span class="badge
                                                                                @if($enrollment->status == 'completed') bg-success
                                                                                @elseif($enrollment->status == 'active') bg-primary
                                                                                @else bg-secondary @endif">
                                                                                {{ $enrollment->status == 'completed' ? 'Завершен' : ($enrollment->status == 'active' ? 'Активен' : 'Приостановлен') }}
                                                                            </span>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6>Общая статистика:</h6>
                                                                    <div class="stats-grid">
                                                                        <div class="stat-item text-center p-3 border rounded">
                                                                            <div class="text-primary fw-bold">{{ $studentEnrollments->count() }}</div>
                                                                            <small class="text-muted">Курсов</small>
                                                                        </div>
                                                                        <div class="stat-item text-center p-3 border rounded">
                                                                            <div class="text-success fw-bold">{{ $completedCourses->count() }}</div>
                                                                            <small class="text-muted">Завершено</small>
                                                                        </div>
                                                                        <div class="stat-item text-center p-3 border rounded">
                                                                            <div class="text-warning fw-bold">{{ round($totalProgress, 1) }}%</div>
                                                                            <small class="text-muted">Средний прогресс</small>
                                                                        </div>
                                                                        <div class="stat-item text-center p-3 border rounded">
                                                                            <div class="text-info fw-bold">{{ round($avgScore ?? 0, 1) }}</div>
                                                                            <small class="text-muted">Средний балл</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-graduate fa-4x text-muted mb-3"></i>
                            <h4>Пока нет студентов</h4>
                            <p class="text-muted">Студенты появятся здесь после записи на ваши курсы</p>
                            <a href="{{ route('teacher.courses.index') }}" class="btn btn-primary">
                                <i class="fas fa-graduation-cap"></i> Управление курсами
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
        .course-badges {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .stat-item {
            transition: all 0.3s ease;
        }
        .stat-item:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
        }
    </style>
@endpush
