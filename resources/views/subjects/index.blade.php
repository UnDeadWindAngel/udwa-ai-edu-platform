@extends('layouts.app')

@section('title', 'Предметы - UDWA AI Edu Platform')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-book"></i> Предметы</h1>
            <p class="lead">Выберите предмет для изучения</p>
        </div>
    </div>

    <div class="row">
        @foreach($subjects as $subject)
            <div class="col-md-4 mb-4">
                <div class="card h-100 subject-card">
                    <div class="card-body text-center">
                        <div class="subject-icon mb-3">
                            <i class="{{ $subject->icon }} fa-3x" style="color: {{ $subject->color }}"></i>
                        </div>
                        <h3 class="card-title">{{ $subject->name }}</h3>
                        <p class="card-text text-muted">{{ $subject->description }}</p>

                        @php
                            $user = auth()->user();
                            if ($user && $user->isStudent()) {
                                $completedLevels = \App\Models\LevelProgress::where('student_id', $user->id)
                                    ->where('subject_id', $subject->id)
                                    ->where('status', 'completed')
                                    ->count();
                                $totalLevels = \App\Models\Level::whereHas('curricula', function($q) use ($subject) {
                                    $q->where('subject_id', $subject->id);
                                })->count();
                            } else {
                                $completedLevels = 0;
                                $totalLevels = 0;
                            }
                        @endphp

                        @if($user && $user->isStudent() && $totalLevels > 0)
                            <div class="progress mb-3">
                                <div class="progress-bar" style="width: {{ ($completedLevels / $totalLevels) * 100 }}%"></div>
                            </div>
                            <small>Завершено: {{ $completedLevels }}/{{ $totalLevels }} уровней</small>
                        @endif
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('subjects.show', $subject) }}" class="btn btn-primary">
                            <i class="fas fa-arrow-right"></i> Выбрать предмет
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($subjects->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>Предметы пока не добавлены</h4>
                    <p>Скоро здесь появятся учебные материалы</p>
                </div>
            </div>
        </div>
    @endif
@endsection
