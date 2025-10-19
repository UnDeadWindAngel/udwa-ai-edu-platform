<div class="row mb-5">
    <div class="col-12">
        <h2>Добро пожаловать, {{ auth()->user()->first_name }}!</h2>
        <p class="text-muted">Ваш прогресс в обучении</p>
    </div>
</div>

<div class="row">
    <!-- Статистика -->
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                <h3>{{ $overallProgress['completed_levels'] ?? 0 }}</h3>
                <p class="mb-0">Завершено уровней</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-spinner fa-2x text-warning mb-2"></i>
                <h3>{{ $overallProgress['in_progress'] ?? 0 }}</h3>
                <p class="mb-0">В процессе</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-chart-line fa-2x text-primary mb-2"></i>
                <h3>{{ $overallProgress['total_progress'] ?? 0 }}%</h3>
                <p class="mb-0">Общий прогресс</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-star fa-2x text-info mb-2"></i>
                <h3>{{ $overallProgress['average_score'] ?? 0 }}</h3>
                <p class="mb-0">Средний балл</p>
            </div>
        </div>
    </div>
</div>

<!-- Активные курсы -->
@if($enrollments->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-book"></i> Мои курсы</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($enrollments as $enrollment)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $enrollment->course->title }}</h5>
                                        <p class="card-text text-muted small">
                                            Прогресс: {{ $enrollment->progress_percentage }}%
                                        </p>
                                        <div class="progress mb-2">
                                            <div class="progress-bar" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                        </div>
                                        <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-primary btn-sm">
                                            Продолжить
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Рекомендуемые предметы -->
@if($recentSubjects->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-compass"></i> Рекомендуемые предметы</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($recentSubjects as $subject)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="{{ $subject->icon }} fa-2x mb-3" style="color: {{ $subject->color }}"></i>
                                        <h5 class="card-title">{{ $subject->name }}</h5>
                                        <p class="card-text small text-muted">{{ Str::limit($subject->description, 100) }}</p>
                                        <a href="{{ route('subjects.show', $subject) }}" class="btn btn-outline-primary btn-sm">
                                            Изучать
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
