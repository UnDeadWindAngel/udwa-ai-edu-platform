@extends('layouts.app')

@section('title', $user->full_name . ' - Детальная информация')

@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Админ панель</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Пользователи</a></li>
                    <li class="breadcrumb-item active">{{ $user->full_name }}</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1><i class="fas fa-user"></i> {{ $user->full_name }}</h1>
                    <p class="text-muted mb-0">Детальная информация о пользователе</p>
                </div>
                <div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Назад
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Основная информация -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Основная информация</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle" width="120" height="120">
                        @else
                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                                 style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                        @endif
                    </div>

                    <table class="table table-sm">
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td>{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>ФИО:</strong></td>
                            <td>{{ $user->full_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Телефон:</strong></td>
                            <td>{{ $user->phone ?? 'Не указан' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Дата рождения:</strong></td>
                            <td>
                                @if($user->birth_date)
                                    {{ $user->birth_date->format('d.m.Y') }} ({{ $user->age }} лет)
                                @else
                                    Не указана
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Статус:</strong></td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">Активен</span>
                                @else
                                    <span class="badge bg-secondary">Неактивен</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Дата регистрации:</strong></td>
                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Последний вход:</strong></td>
                            <td>
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->format('d.m.Y H:i') }}
                                @else
                                    <span class="text-muted">Никогда</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    @if($user->bio)
                        <div class="mt-3">
                            <strong>О себе:</strong>
                            <p class="mt-1">{{ $user->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Управление ролями -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-tag"></i> Управление ролями</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update-roles', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @foreach($roles as $role)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox"
                                       name="roles[]" value="{{ $role->id }}"
                                       id="role_{{ $role->id }}"
                                    {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_{{ $role->id }}">
                                    <strong>{{ $role->display_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $role->description }}</small>
                                </label>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-primary w-100 mt-3">
                            <i class="fas fa-save"></i> Сохранить роли
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Дополнительная информация -->
        <div class="col-md-8">
            @if($user->isStudent())
                <!-- Информация для студента -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Учебная информация</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>ID студента:</strong> {{ $user->student_id ?? 'Не указан' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Класс/курс:</strong> {{ $user->grade ?? 'Не указан' }}</p>
                            </div>
                        </div>

                        @if($user->levelProgress->count() > 0)
                            <h6>Прогресс обучения:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>Предмет</th>
                                        <th>Уровень</th>
                                        <th>Статус</th>
                                        <th>Прогресс</th>
                                        <th>Оценка</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($user->levelProgress as $progress)
                                        <tr>
                                            <td>{{ $progress->subject->name }}</td>
                                            <td>{{ $progress->level->name }}</td>
                                            <td>
                                    <span class="badge
                                        @if($progress->status === 'completed') bg-success
                                        @elseif($progress->status === 'in_progress') bg-primary
                                        @else bg-secondary @endif">
                                        {{ $progress->status === 'completed' ? 'Завершено' : ($progress->status === 'in_progress' ? 'В процессе' : 'Не начато') }}
                                    </span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 6px; width: 80px;">
                                                    <div class="progress-bar" style="width: {{ $progress->progress_percentage }}%"></div>
                                                </div>
                                                <small>{{ $progress->progress_percentage }}%</small>
                                            </td>
                                            <td>
                                                @if($progress->score)
                                                    <span class="fw-bold">{{ $progress->score }}</span>
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
                            <p class="text-muted">Студент еще не начал обучение</p>
                        @endif
                    </div>
                </div>
            @endif

            @if($user->isTeacher())
                <!-- Информация для учителя -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chalkboard-teacher"></i> Преподавательская деятельность</h5>
                    </div>
                    <div class="card-body">
                        @if($user->createdCourses->count() > 0)
                            <h6>Созданные курсы:</h6>
                            <div class="list-group">
                                @foreach($user->createdCourses as $course)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $course->title }}</h6>
                                                <small class="text-muted">
                                                    Статус:
                                                    @if($course->is_approved && $course->is_active)
                                                        <span class="text-success">Одобрен и активен</span>
                                                    @elseif($course->is_approved && !$course->is_active)
                                                        <span class="text-warning">Одобрен, но неактивен</span>
                                                    @else
                                                        <span class="text-danger">На модерации</span>
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted">
                                                    Студентов: {{ $course->enrollments->count() }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Учитель еще не создал курсы</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Системные действия -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> Системные действия</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-{{ $user->is_active ? 'warning' : 'success' }} w-100">
                                    <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                    {{ $user->is_active ? 'Деактивировать' : 'Активировать' }}
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6 mb-2">
                            <button class="btn btn-outline-info w-100" disabled>
                                <i class="fas fa-envelope"></i> Отправить email
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
