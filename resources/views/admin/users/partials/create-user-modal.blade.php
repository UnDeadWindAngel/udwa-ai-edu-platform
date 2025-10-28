<!-- Модальное окно создания пользователя -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">
                    <i class="fas fa-user-plus"></i> Создать нового пользователя
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="role" id="createUserRole" value="student">

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Создается пользователь с ролью: <strong id="currentRoleDisplay">Студент</strong>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">Имя *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Фамилия *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="middle_name" class="form-label">Отчество</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Телефон</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Пароль *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Минимум 8 символов</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Подтверждение пароля *</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>

                    <!-- Дополнительные поля для студентов -->
                    <div id="studentFields">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="student_id" class="form-label">ID студента</label>
                                    <input type="text" class="form-control" id="student_id" name="student_id">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="grade" class="form-label">Класс/Курс</label>
                                    <input type="text" class="form-control" id="grade" name="grade" placeholder="Например: 10А или 1 курс">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Дополнительные поля для учителей -->
                    <div id="teacherFields" style="display: none;">
                        <div class="mb-3">
                            <label for="bio" class="form-label">Краткая информация</label>
                            <textarea class="form-control" id="bio" name="bio" rows="2" placeholder="Опыт работы, специализация..."></textarea>
                        </div>
                    </div>

                    <!-- Дополнительные поля для родителей -->
                    <div id="parentFields" style="display: none;">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            После создания аккаунта родителя необходимо привязать детей через раздел "Связи Родитель-Студент"
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="birth_date" class="form-label">Дата рождения</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Настройки</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">Активный аккаунт</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="send_welcome_email" name="send_welcome_email" value="1">
                                    <label class="form-check-label" for="send_welcome_email">Отправить приветственное письмо</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Отмена
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Создать пользователя
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
