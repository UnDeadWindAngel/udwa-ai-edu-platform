@extends('layouts.app')

@section('title', 'Управление пользователями - Админ панель')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1><i class="fas fa-users-cog"></i> Управление пользователями</h1>
                    <p class="lead">Управление пользователями и их ролями</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('admin.users.search') }}" class="btn btn-outline-primary">
                        <i class="fas fa-search-plus"></i> Расширенный поиск
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-success">
                        <i class="fas fa-user-plus"></i> Создать пользователя
                    </a>
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        Быстрое создание
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item create-user-option" href="#" data-role="student">
                                <i class="fas fa-user-graduate text-primary"></i> Студента
                            </a></li>
                        <li><a class="dropdown-item create-user-option" href="#" data-role="teacher">
                                <i class="fas fa-chalkboard-teacher text-success"></i> Учителя
                            </a></li>
                        <li><a class="dropdown-item create-user-option" href="#" data-role="parent">
                                <i class="fas fa-user-friends text-info"></i> Родителя
                            </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item create-user-option" href="#" data-role="admin">
                                <i class="fas fa-user-shield text-danger"></i> Администратора
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Фильтры и поиск -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-search-form
                        :action="route('admin.users.index')"
                        :search-value="request('search')"
                        :role-value="request('role')"
                        :status-value="request('status')"
                        :roles="$roles"
                        form-id="usersSearchForm"
                    />
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
                    <div>
                        <span class="badge bg-primary">{{ $users->total() }} пользователей</span>
                        @if(request()->hasAny(['search', 'role', 'status']))
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                                <i class="fas fa-times"></i> Очистить фильтры
                            </a>
                        @endif
                    </div>
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
                                                    <button type="submit" class="btn btn-sm btn-outline-{{ $user->is_active ? 'warning' : 'success' }}"
                                                            title="{{ $user->is_active ? 'Деактивировать' : 'Активировать' }}"
                                                            onclick="return confirm('Вы уверены, что хотите {{ $user->is_active ? 'деактивировать' : 'активировать' }} пользователя {{ $user->full_name }}?')">
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
                                                        <form action="{{ route('admin.users.update-roles', $user) }}" method="POST">
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
                            <a href="{{ route('admin.users.search') }}" class="btn btn-primary">
                                <i class="fas fa-search-plus"></i> Перейти к расширенному поиску
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Подключение модального окна создания пользователя -->
    @include('admin.users.partials.create-user-modal')

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const createUserModal = new bootstrap.Modal(document.getElementById('createUserModal'));
                const createUserRoleInput = document.getElementById('createUserRole');
                const currentRoleDisplay = document.getElementById('currentRoleDisplay');
                const studentFields = document.getElementById('studentFields');
                const teacherFields = document.getElementById('teacherFields');
                const parentFields = document.getElementById('parentFields');

                // Обработчики для кнопок создания пользователя
                document.querySelectorAll('.create-user-option').forEach(option => {
                    option.addEventListener('click', function(e) {
                        e.preventDefault();
                        const role = this.getAttribute('data-role');
                        createUserRoleInput.value = role;

                        // Обновляем отображаемую роль
                        const roleNames = {
                            'student': 'Студент',
                            'teacher': 'Учитель',
                            'parent': 'Родитель',
                            'admin': 'Администратор'
                        };
                        currentRoleDisplay.textContent = roleNames[role];

                        // Показываем/скрываем дополнительные поля
                        studentFields.style.display = role === 'student' ? 'block' : 'none';
                        teacherFields.style.display = role === 'teacher' ? 'block' : 'none';
                        parentFields.style.display = role === 'parent' ? 'block' : 'none';

                        // Очищаем форму
                        document.getElementById('createUserForm').reset();

                        createUserModal.show();
                    });
                });

                // Генерация пароля
                document.getElementById('generatePassword').addEventListener('click', function() {
                    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
                    let password = "";
                    for (let i = 0; i < 12; i++) {
                        password += charset.charAt(Math.floor(Math.random() * charset.length));
                    }
                    document.getElementById('password').value = password;
                    document.getElementById('password_confirmation').value = password;
                });
            });
        </script>
    @endpush
@endsection
