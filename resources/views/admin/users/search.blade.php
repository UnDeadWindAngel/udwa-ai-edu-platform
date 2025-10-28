@extends('layouts.app')

@section('title', 'Поиск пользователей')

@section('content')
    <div class="container-fluid py-4">
        <!-- Заголовок и навигация -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Админ-панель</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Пользователи</a></li>
                                <li class="breadcrumb-item active">Поиск</li>
                            </ol>
                        </nav>
                        <h1 class="h2 mb-0">Поиск пользователей</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Форма поиска -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-search"></i> Расширенный поиск пользователей</h5>
                    </div>
                    <div class="card-body">
                        <x-search-form
                            :action="route('admin.users.search')"
                            :search-value="request('search')"
                            :role-value="request('role')"
                            :status-value="request('status')"
                            :roles="$roles"
                            search-placeholder="Введите имя, фамилию, email или ID студента..."
                            form-id="advancedSearchForm"
                        />

                        <!-- Дополнительные опции поиска -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="include_inactive" name="include_inactive"
                                           value="1" {{ request('include_inactive') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="include_inactive">
                                        Включая неактивных пользователей
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="exact_match" name="exact_match"
                                           value="1" {{ request('exact_match') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="exact_match">
                                        Точное совпадение
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Результаты поиска -->
        @if(isset($users) && $users->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Результаты поиска ({{ $users->total() }})</h5>
                            <div class="text-muted small">
                                Показано {{ $users->firstItem() }} - {{ $users->lastItem() }} из {{ $users->total() }}
                            </div>
                        </div>
                        <div class="card-body">
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
                                        <th>Действия</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        <div class="avatar-title bg-primary rounded-circle text-white">
                                                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $user->full_name }}</h6>
                                                        <small class="text-muted">{{ $user->student_id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @foreach($user->roles as $role)
                                                    <span class="badge bg-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'teacher' ? 'warning' : 'primary') }}">
                                                {{ $role->name }}
                                            </span>
                                                @endforeach
                                            </td>
                                            <td>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                            {{ $user->is_active ? 'Активен' : 'Неактивен' }}
                                        </span>
                                            </td>
                                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.users.show', $user) }}"
                                                       class="btn btn-sm btn-outline-primary" title="Просмотр">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.users.edit', $user) }}"
                                                       class="btn btn-sm btn-outline-warning" title="Редактировать">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($user->hasRole('student'))
                                                        <button type="button" class="btn btn-sm btn-outline-info"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#addParentModal"
                                                                data-student-id="{{ $user->id }}"
                                                                data-student-name="{{ $user->full_name }}"
                                                                title="Добавить родителя">
                                                            <i class="fas fa-user-plus"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Пагинация -->
                            @if($users->hasPages())
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="text-muted">
                                        Показано с {{ $users->firstItem() }} по {{ $users->lastItem() }} из {{ $users->total() }} записей
                                    </div>
                                    {{ $users->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @elseif(request()->hasAny(['search', 'role', 'status']))
            <!-- Нет результатов -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4>Пользователи не найдены</h4>
                            <p class="text-muted">Попробуйте изменить параметры поиска</p>
                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('searchForm').reset();">
                                <i class="fas fa-redo"></i> Сбросить фильтры
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Модальное окно добавления родителя -->
    <div class="modal fade" id="addParentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Добавить родителя для студента</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addParentForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="student_id" name="student_id">

                        <div class="mb-3">
                            <label class="form-label">Студент:</label>
                            <p class="form-control-plaintext fw-bold" id="student_name"></p>
                        </div>

                        <div class="mb-3">
                            <label for="parent_search" class="form-label">Поиск родителя:</label>
                            <input type="text" class="form-control" id="parent_search"
                                   placeholder="Введите имя, фамилию или email родителя...">
                            <div class="form-text">Начните вводить для поиска существующих пользователей с ролью "родитель"</div>
                        </div>

                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Выберите родителя:</label>
                            <select class="form-select" id="parent_id" name="parent_id" required>
                                <option value="">-- Выберите родителя --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="relationship_type" class="form-label">Тип связи:</label>
                            <select class="form-select" id="relationship_type" name="relationship_type">
                                <option value="parent">Родитель</option>
                                <option value="guardian">Опекун</option>
                                <option value="relative">Родственник</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_primary" name="is_primary" value="1">
                                    <label class="form-check-label" for="is_primary">Основной родитель</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="can_view_progress" name="can_view_progress" value="1" checked>
                                    <label class="form-check-label" for="can_view_progress">Может просматривать прогресс</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Добавить связь</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Обработка модального окна добавления родителя
            const addParentModal = document.getElementById('addParentModal');
            addParentModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const studentId = button.getAttribute('data-student-id');
                const studentName = button.getAttribute('data-student-name');

                document.getElementById('student_id').value = studentId;
                document.getElementById('student_name').textContent = studentName;
                document.getElementById('addParentForm').action = `/admin/parent-relationships`;
            });

            // Поиск родителей при вводе
            const parentSearch = document.getElementById('parent_search');
            const parentSelect = document.getElementById('parent_id');

            parentSearch.addEventListener('input', function() {
                const searchTerm = this.value.trim();

                if (searchTerm.length < 2) {
                    parentSelect.innerHTML = '<option value="">-- Выберите родителя --</option>';
                    return;
                }

                // Здесь будет AJAX запрос для поиска родителей
                fetch(`/admin/users/search-parents?search=${encodeURIComponent(searchTerm)}`)
                    .then(response => response.json())
                    .then(data => {
                        parentSelect.innerHTML = '<option value="">-- Выберите родителя --</option>';
                        data.forEach(parent => {
                            const option = document.createElement('option');
                            option.value = parent.id;
                            option.textContent = `${parent.full_name} (${parent.email})`;
                            parentSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    </script>
@endpush
