<div class="row mb-5">
    <div class="col-12">
        <h2>Добро пожаловать, {{ auth()->user()->first_name }}!</h2>
        <p class="text-muted">Панель управления преподавателя</p>
    </div>
</div>

<!-- Статистика учителя -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-book fa-2x text-primary mb-2"></i>
                <h3>{{ $createdCourses->count() }}</h3>
                <p class="mb-0">Мои курсы</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-users fa-2x text-success mb-2"></i>
                <h3>{{ $totalStudents }}</h3>
                <p class="mb-0">Студентов</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-chart-line fa-2x text-warning mb-2"></i>
                <h3>85%</h3>
                <p class="mb-0">Успеваемость</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-tasks fa-2x text-info mb-2"></i>
                <h3>12</h3>
                <p class="mb-0">Заданий</p>
            </div>
        </div>
    </div>
</div>

<!-- Мои курсы -->
@if($createdCourses->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-book"></i> Мои курсы</h4>
                    <a href="{{ route('teacher.courses.index') }}" class="btn btn-sm btn-primary">
                        Управление курсами
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($createdCourses as $course)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $course->name }}</h5>
                                        <p class="card-text text-muted small">
                                            {{ Str::limit($course->description, 80) }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                Студентов: {{ $course->enrollments_count }}
                                            </small>
                                            <div>
                                                <a href="{{ route('teacher.courses.show', $course) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    Просмотр
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h4>У вас пока нет курсов</h4>
                    <p class="text-muted">Создайте свой первый курс для начала работы</p>
                    <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary">
                        Создать курс
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Быстрые действия -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-bolt"></i> Быстрые действия</h4>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-2 col-6 mb-3">
                        <a href="{{ route('teacher.courses.create') }}" class="text-decoration-none">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <i class="fas fa-plus fa-2x text-primary mb-2"></i>
                                    <p class="mb-0 small">Новый курс</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 col-6 mb-3">
                        <a href="{{ route('teacher.students') }}" class="text-decoration-none">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <i class="fas fa-users fa-2x text-success mb-2"></i>
                                    <p class="mb-0 small">Студенты</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 col-6 mb-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <i class="fas fa-tasks fa-2x text-warning mb-2"></i>
                                    <p class="mb-0 small">Задания</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 col-6 mb-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <i class="fas fa-chart-bar fa-2x text-info mb-2"></i>
                                    <p class="mb-0 small">Аналитика</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 col-6 mb-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <i class="fas fa-comments fa-2x text-secondary mb-2"></i>
                                    <p class="mb-0 small">Сообщения</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2 col-6 mb-3">
                        <a href="{{ route('profile') }}" class="text-decoration-none">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <i class="fas fa-cog fa-2x text-dark mb-2"></i>
                                    <p class="mb-0 small">Настройки</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
