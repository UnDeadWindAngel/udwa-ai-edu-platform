@extends('layouts.app')

@section('title', 'Модерация курсов - Админ панель')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-graduation-cap"></i> Модерация курсов</h1>
            <p class="lead">Управление и модерация учебных курсов</p>
        </div>
    </div>

    <!-- Статистика -->
    @php
        $pendingCount = $courses->where('is_approved', false)->count();
        $activeCount = $courses->where('is_approved', true)->where('is_active', true)->count();
        $inactiveCount = $courses->where('is_approved', true)->where('is_active', false)->count();
    @endphp

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                    <h3>{{ $pendingCount }}</h3>
                    <p class="mb-0">На модерации</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-play-circle fa-2x text-success mb-2"></i>
                    <h3>{{ $activeCount }}</h3>
                    <p class="mb-0">Активных</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-pause-circle fa-2x text-secondary mb-2"></i>
                    <h3>{{ $inactiveCount }}</h3>
                    <p class="mb-0">Неактивных</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Таблица курсов -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Список курсов</h4>
                    <span class="badge bg-primary">{{ $courses->total() }} курсов</span>
                </div>
                <div class="card-body">
                    @if($courses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Предмет</th>
                                    <th>Уровень</th>
                                    <th>Автор</th>
                                    <th>Статус</th>
                                    <th>Студентов</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($courses as $course)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $course->title }}</strong>
                                                @if($course->description)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($course->description, 50) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <i class="{{ $course->curriculum->subject->icon }} me-1"
                                               style="color: {{ $course->curriculum->subject->color }}"></i>
                                            {{ $course->curriculum->subject->name }}
                                        </td>
                                        <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $course->curriculum->level->name }}
                                        </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($course->author->avatar)
                                                    <img src="{{ asset('storage/' . $course->author->avatar) }}"
                                                         alt="Avatar" class="rounded-circle me-2" width="24" height="24">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                                         style="width: 24px; height: 24px;">
                                                        <i class="fas fa-user fa-xs"></i>
                                                    </div>
                                                @endif
                                                {{ $course->author->full_name }}
                                            </div>
                                        </td>
                                        <td>
                                            @if(!$course->is_approved)
                                                <span class="badge bg-warning">На модерации</span>
                                            @elseif($course->is_active)
                                                <span class="badge bg-success">Активен</span>
                                            @else
                                                <span class="badge bg-secondary">Неактивен</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $course->enrollments_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <small>{{ $course->created_at->format('d.m.Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="#" class="btn btn-outline-primary" title="Просмотр">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if(!$course->is_approved)
                                                    <form action="{{ route('admin.content.approve-course', $course) }}"
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success" title="Одобрить">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.content.reject-course', $course) }}"
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger" title="Отклонить">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    @if($course->is_active)
                                                        <form action="{{ route('admin.content.reject-course', $course) }}"
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-warning" title="Деактивировать">
                                                                <i class="fas fa-pause"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('admin.content.approve-course', $course) }}"
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-success" title="Активировать">
                                                                <i class="fas fa-play"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Пагинация -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $courses->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-graduation-cap fa-4x text-muted mb-3"></i>
                            <h4>Курсы не найдены</h4>
                            <p class="text-muted">Пока нет курсов для модерации</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
