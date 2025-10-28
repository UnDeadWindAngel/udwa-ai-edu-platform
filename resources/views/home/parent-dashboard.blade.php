<div class="row mb-5">
    <div class="col-12">
        <h2>Добро пожаловать, {{ auth()->user()->first_name }}!</h2>
        <p class="text-muted">Панель родительского контроля</p>
    </div>
</div>

@if($children->count() > 0)
    <!-- Дети и их прогресс -->
    <div class="row">
        @foreach($children as $child)
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-child text-primary me-2"></i>
                            {{ $child->full_name }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($child->levelProgresses->count() > 0)
                            <h6>Последний прогресс:</h6>
                            @foreach($child->levelProgresses as $progress)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                    <div>
                                        <strong>{{ $progress->level->subject->name }}</strong>
                                        <br>
                                        <small class="text-muted">Уровень: {{ $progress->level->name }}</small>
                                    </div>
                                    <div class="text-end">
                            <span class="badge bg-{{ $progress->status == 'completed' ? 'success' : 'warning' }}">
                                {{ $progress->status == 'completed' ? 'Завершено' : 'В процессе' }}
                            </span>
                                        <br>
                                        <small>Прогресс: {{ $progress->progress_percentage }}%</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">Нет данных о прогрессе</p>
                        @endif

                        <div class="mt-3">
                            <a href="{{ route('parent.child-progress', $child->id) }}"
                               class="btn btn-primary btn-sm">
                                Подробный прогресс
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Общая статистика -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-chart-pie"></i> Общая статистика детей</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h4>{{ $children->sum(fn($child) => $child->levelProgresses->where('status', 'completed')->count()) }}</h4>
                                    <p class="mb-0">Завершено уровней</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h4>{{ $children->sum(fn($child) => $child->levelProgresses->where('status', 'in_progress')->count()) }}</h4>
                                    <p class="mb-0">В процессе</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h4>{{ round($children->avg(fn($child) => $child->levelProgresses->avg('progress_percentage')) ?? 0) }}%</h4>
                                    <p class="mb-0">Средний прогресс</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h4>{{ round($children->avg(fn($child) => $child->levelProgresses->avg('score')) ?? 0, 1) }}</h4>
                                    <p class="mb-0">Средний балл</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Нет привязанных детей -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-child fa-3x text-muted mb-3"></i>
                    <h4>Нет привязанных детей</h4>
                    <p class="text-muted">Для просмотра прогресса необходимо привязать аккаунты детей</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addChildModal">
                        Привязать ребенка
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Модальное окно для привязки ребенка -->
<div class="modal fade" id="addChildModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Привязать ребенка</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('parent.add-child') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="student_id" class="form-label">ID студента</label>
                        <input type="text" class="form-control" id="student_id" name="student_id"
                               placeholder="Введите ID студента" required>
                        <div class="form-text">ID можно получить у администратора или у самого студента</div>
                    </div>
                    <div class="mb-3">
                        <label for="relationship_type" class="form-label">Тип связи</label>
                        <select class="form-select" id="relationship_type" name="relationship_type">
                            <option value="parent">Родитель</option>
                            <option value="guardian">Опекун</option>
                            <option value="relative">Родственник</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_primary" name="is_primary" value="1">
                        <label class="form-check-label" for="is_primary">
                            Основной родитель
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Привязать</button>
                </form>
            </div>
        </div>
    </div>
</div>
