@extends('layouts.app')

@section('title', 'Редактирование ролей: ' . $user->full_name)

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Админ-панель</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Пользователи</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.users.show', $user) }}">{{ $user->full_name }}</a></li>
                                <li class="breadcrumb-item active">Редактирование ролей</li>
                            </ol>
                        </nav>
                        <h1 class="h2 mb-0">Редактирование ролей: {{ $user->full_name }}</h1>
                    </div>
                    <div>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Назад
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Назначение ролей</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.users.update-roles', $user) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <h6>Текущие роли пользователя:</h6>
                                @if($user->roles->count() > 0)
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-primary fs-6">{{ $role->display_name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">Пользователь не имеет назначенных ролей</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Выберите роли:</label>
                                <div class="row">
                                    @foreach($roles as $role)
                                        <div class="col-md-6 mb-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="roles[]"
                                                               value="{{ $role->id }}"
                                                               id="role_{{ $role->id }}"
                                                            {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold" for="role_{{ $role->id }}">
                                                            {{ $role->display_name }}
                                                        </label>
                                                        <div class="text-muted small mt-1">
                                                            {{ $role->description }}
                                                        </div>
                                                        <div class="text-muted small">
                                                            <i class="fas fa-users"></i>
                                                            {{ $role->users_count }} пользователей
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @error('roles')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Внимание:</strong> Изменение ролей пользователя может повлиять на его доступ к функциям системы.
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Отмена
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Сохранить изменения
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Информация о пользователе -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Информация о пользователе</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-primary rounded-circle text-white display-6">
                                    {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                </div>
                            </div>
                            <h5>{{ $user->full_name }}</h5>
                            <p class="text-muted">{{ $user->email }}</p>
                        </div>

                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>ID:</strong></td>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Статус:</strong></td>
                                <td>
                                <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                    {{ $user->is_active ? 'Активен' : 'Неактивен' }}
                                </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Зарегистрирован:</strong></td>
                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Подсказки по ролям -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-lightbulb"></i> О ролях</h5>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            <p><strong>Администратор:</strong> Полный доступ ко всем функциям системы</p>
                            <p><strong>Учитель:</strong> Создание курсов, управление студентами</p>
                            <p><strong>Студент:</strong> Доступ к обучению, прохождение курсов</p>
                            <p><strong>Родитель:</strong> Просмотр прогресса детей</p>
                            <p><strong>Модератор:</strong> Модерация контента, управление курсами</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
