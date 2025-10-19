@extends('layouts.app')

@section('title', 'Управление пользователями - Админ панель')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-users-cog"></i> Управление пользователями</h1>
            <p class="lead">Управление пользователями и их ролями</p>
        </div>
    </div>

    <!-- Фильтры и поиск -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Поиск</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="Поиск по имени или email...">
                        </div>
                        <div class="col-md-3">
                            <label for="role" class="form-label">Роль</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">Все роли</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                        {{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Статус</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Все статусы</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активные</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Неактивные</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Поиск
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Таблица пользователей -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Список пользователей</h4>
                    <span class="badge bg-primary">{{ $users->total() }} пользователей</span>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Пользователь</th>
                                    <th>Email</th>
                                    <th>Роли</th>
                                    <th>Статус</th>
                                    <th>Дата регистрации</th>
                                    <th>Последний вход</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($user->avatar)
                                                    <img src="{{ asset('storage/' . $user->avatar) }}"
                                                         alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                                         style="width: 32px; height: 32px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $user->full_name }}</div>
                                                    @if($user->student_id)
                                                        <small class="text-muted">{{ $user->student_id }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge
                                                @if($role->name == 'admin') bg-danger
                                                @elseif($role->name == 'teacher') bg-success
                                                @elseif($role->name == 'moderator') bg-warning
                                                @elseif($role->name == 'student') bg-primary
                                                @elseif($role->name == 'parent') bg-info
                                                @else bg-secondary @endif">
                                                {{ $role->display_name }}
                                            </span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($user->is_active)
                                                <span class="badge bg-success">Активен</span>
                                            @else
                                                <span class="badge bg-secondary">Неактивен</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $user->created_at->format('d.m.Y') }}</small>
                                        </td>
                                        <td>
                                            @if($user->last_login_at)
                                                <small>{{ $user->last_login_at->diffForHumans() }}</small>
                                            @else
                                                <small class="text-muted">Никогда</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.users.show', $user) }}"
                                                   class="btn btn-outline-primary" title="Просмотр">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editRolesModal{{ $user->id }}"
                                                        title="Изменить роли">
                                                    <i class="fas fa-user-tag"></i>
                                                </button>
                                                <form action="{{ route('admin.users.toggle-status', $user) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-{{ $user->is_active ? 'warning' : 'success' }}"
                                                            title="{{ $user->is_active ? 'Деактивировать' : 'Активировать' }}">
                                                        <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Модальное окно редактирования ролей -->
                                            <div class="modal fade" id="editRolesModal{{ $user->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Изменение ролей для {{ $user->full_name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="{{ route('admin.users.update-role', $user) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                @foreach($roles as $role)
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox"
                                                                               name="roles[]" value="{{ $role->id }}"
                                                                               id="role{{ $role->id }}_{{ $user->id }}"
                                                                            {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                                                                        <label class="form-check-label" for="role{{ $role->id }}_{{ $user->id }}">
                                                                            {{ $role->display_name }}
                                                                        </label>
                                                                        <small class="text-muted d-block">{{ $role->description }}</small>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Пагинация -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users-slash fa-4x text-muted mb-3"></i>
                            <h4>Пользователи не найдены</h4>
                            <p class="text-muted">Попробуйте изменить параметры поиска</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
