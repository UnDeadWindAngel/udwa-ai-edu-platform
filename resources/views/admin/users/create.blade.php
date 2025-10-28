@extends('layouts.app')

@section('title', 'Создание пользователя - Админ панель')

@section('content')
    <div class="container-fluid py-4">
        <!-- Заголовок и навигация -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Админ-панель</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Пользователи</a></li>
                        <li class="breadcrumb-item active">Создание пользователя</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-0">
                            <i class="fas fa-user-plus text-primary"></i> Создание пользователя
                        </h1>
                        <p class="lead mb-0">Добавление нового пользователя в систему</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Назад к списку
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Форма создания пользователя -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-edit"></i> Основная информация</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
                            @csrf

                            <!-- Выбор роли -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label class="form-label fw-bold">Роль пользователя *</label>
                                    <div class="row">
                                        @foreach($roles as $role)
                                            <div class="col-md-3 mb-3">
                                                <div class="card role-card">
                                                    <div class="card-body text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="role"
                                                                   value="{{ $role->name }}" id="role_{{ $role->name }}"
                                                                {{ $loop->first ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold" for="role_{{ $role->name }}">
                                                                <i class="fas
                                                            @if($role->name == 'student') fa-user-graduate
                                                            @elseif($role->name == 'teacher') fa-chalkboard-teacher
                                                            @elseif($role->name == 'parent') fa-user-friends
                                                            @elseif($role->name == 'admin') fa-user-shield
                                                            @else fa-user @endif
                                                            fa-2x mb-2 text-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'teacher' ? 'success' : 'primary') }}"></i>
                                                                <br>
                                                                {{ $role->display_name }}
                                                            </label>
                                                        </div>
                                                        <small class="text-muted">{{ $role->description }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Основные поля -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">Имя *</label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                               id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                        @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Фамилия *</label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                               id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                        @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="middle_name" class="form-label">Отчество</label>
                                        <input type="text" class="form-control @error('middle_name') is-invalid @enderror"
                                               id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                                        @error('middle_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Телефон</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                               id="phone" name="phone" value="{{ old('phone') }}">
                                        @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Пароль *</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                               id="password" name="password" required>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Минимум 8 символов</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Подтверждение пароля *</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                               name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Дополнительные поля для студентов -->
                            <div class="role-specific-fields" id="studentFields">
                                <hr>
                                <h6><i class="fas fa-user-graduate text-primary"></i> Информация для студента</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="student_id" class="form-label">ID студента</label>
                                            <input type="text" class="form-control @error('student_id') is-invalid @enderror"
                                                   id="student_id" name="student_id" value="{{ old('student_id') }}">
                                            @error('student_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="grade" class="form-label">Класс/Курс</label>
                                            <input type="text" class="form-control @error('grade') is-invalid @enderror"
                                                   id="grade" name="grade" value="{{ old('grade') }}"
                                                   placeholder="Например: 10А или 1 курс">
                                            @error('grade')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Дополнительные поля для учителей -->
                            <div class="role-specific-fields" id="teacherFields" style="display: none;">
                                <hr>
                                <h6><i class="fas fa-chalkboard-teacher text-success"></i> Информация для учителя</h6>
                                <div class="mb-3">
                                    <label for="bio" class="form-label">Краткая информация</label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror" id="bio"
                                              name="bio" rows="3" placeholder="Опыт работы, специализация...">{{ old('bio') }}</textarea>
                                    @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Дополнительные поля для родителей -->
                            <div class="role-specific-fields" id="parentFields" style="display: none;">
                                <hr>
                                <h6><i class="fas fa-user-friends text-info"></i> Информация для родителя</h6>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    После создания аккаунта родителя необходимо привязать детей через раздел "Связи Родитель-Студент"
                                </div>
                            </div>

                            <!-- Дополнительная информация -->
                            <hr>
                            <h6><i class="fas fa-info-circle"></i> Дополнительная информация</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="birth_date" class="form-label">Дата рождения</label>
                                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                               id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                                        @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Адрес</label>
                                        <input type="text" class="form-control @error('registration_address') is-invalid @enderror"
                                               id="registration_address" name="registration_address"
                                               value="{{ old('registration_address') }}" placeholder="Адрес регистрации">
                                        @error('registration_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Настройки аккаунта -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Настройки аккаунта</label>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                                {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Активный аккаунт
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="send_welcome_email" name="send_welcome_email" value="1"
                                                {{ old('send_welcome_email') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="send_welcome_email">
                                                Отправить приветственное письмо
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="require_password_change" name="require_password_change" value="1"
                                                {{ old('require_password_change') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="require_password_change">
                                                Требовать смену пароля при первом входе
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Контакты для экстренных случаев</label>
                                        <textarea class="form-control" id="emergency_contacts" name="emergency_contacts"
                                                  rows="3" placeholder="ФИО, телефон, отношение к пользователю">{{ old('emergency_contacts') }}</textarea>
                                        <div class="form-text">Для студентов и несовершеннолетних пользователей</div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Отмена
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Создать пользователя
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .role-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .role-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .role-card .form-check-input:checked ~ .form-check-label {
            color: #0d6efd;
            font-weight: bold;
        }

        .role-card .form-check-input:checked ~ .text-muted {
            color: #0d6efd !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const studentFields = document.getElementById('studentFields');
            const teacherFields = document.getElementById('teacherFields');
            const parentFields = document.getElementById('parentFields');

            function toggleRoleFields() {
                const selectedRole = document.querySelector('input[name="role"]:checked').value;

                // Скрываем все поля
                document.querySelectorAll('.role-specific-fields').forEach(field => {
                    field.style.display = 'none';
                });

                // Показываем соответствующие поля
                if (selectedRole === 'student') {
                    studentFields.style.display = 'block';
                } else if (selectedRole === 'teacher') {
                    teacherFields.style.display = 'block';
                } else if (selectedRole === 'parent') {
                    parentFields.style.display = 'block';
                }
            }

            // Обработчики для радиокнопок
            roleRadios.forEach(radio => {
                radio.addEventListener('change', toggleRoleFields);

                // Добавляем обработчик клика на карточку
                const card = radio.closest('.card');
                if (card) {
                    card.addEventListener('click', function() {
                        radio.checked = true;
                        toggleRoleFields();
                    });
                }
            });

            // Инициализация при загрузке
            toggleRoleFields();
        });
    </script>
@endpush
