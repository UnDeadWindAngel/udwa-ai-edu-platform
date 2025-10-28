<div class="row mb-5">
    <div class="col-12">
        <h2>Добро пожаловать, {{ auth()->user()->first_name }}!</h2>
        <p class="text-muted">Панель администратора системы</p>
    </div>
</div>

<!-- Статистика системы -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                        <p class="mb-0">Пользователей</p>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="mb-0">{{ $stats['total_subjects'] }}</h2>
                        <p class="mb-0">Предметов</p>
                    </div>
                    <i class="fas fa-book fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="mb-0">{{ $stats['active_courses'] }}</h2>
                        <p class="mb-0">Активных курсов</p>
                    </div>
                    <i class="fas fa-play-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="mb-0">{{ $stats['pending_approvals'] }}</h2>
                        <p class="mb-0">На модерации</p>
                    </div>
                    <i class="fas fa-clock fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Быстрые действия администратора -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cogs"></i> Управление системой</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                        Админ панель
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2 text-success"></i>
                        Управление пользователями
                    </a>
                    <a href="{{ route('admin.content.courses') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-book me-2 text-info"></i>
                        Управление контентом
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-bar me-2 text-warning"></i>
                        Аналитика системы
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-cog me-2 text-secondary"></i>
                        Настройки системы
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock"></i> Последние действия</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <small class="text-muted">2 минуты назад</small>
                            <p class="mb-1">Новый пользователь зарегистрирован</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <small class="text-muted">15 минут назад</small>
                            <p class="mb-1">Курс "Математика для начинающих" завершен</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <small class="text-muted">1 час назад</small>
                            <p class="mb-1">Новый курс ожидает модерации</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <small class="text-muted">2 часа назад</small>
                            <p class="mb-1">Обновление системы завершено</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Системная информация -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Системная информация</h5>
                <span class="badge bg-success">Online</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Версия системы:</strong> UDWA AI Edu Platform v1.0
                    </div>
                    <div class="col-md-4">
                        <strong>Последнее обновление:</strong> {{ now()->format('d.m.Y H:i') }}
                    </div>
                    <div class="col-md-4">
                        <strong>Статус:</strong> <span class="text-success">✓ Работает нормально</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    .timeline-content {
        padding-bottom: 10px;
    }
</style>
