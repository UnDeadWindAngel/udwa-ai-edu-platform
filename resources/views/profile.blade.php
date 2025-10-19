@extends('layouts.app')

@section('title', 'Профиль - ' . $user->full_name)

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle" width="120" height="120">
                        @else
                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                        @endif
                    </div>
                    <h3>{{ $user->full_name }}</h3>
                    <p class="text-muted">
                        @foreach($user->roles as $role)
                            <span class="badge bg-secondary">{{ $role->display_name }}</span>
                        @endforeach
                    </p>

                    @if($user->bio)
                        <p class="mt-3">{{ $user->bio }}</p>
                    @endif

                    <div class="mt-3">
                        <p class="mb-1"><small class="text-muted"><i class="fas fa-envelope"></i> {{ $user->email }}</small></p>
                        @if($user->phone)
                            <p class="mb-1"><small class="text-muted"><i class="fas fa-phone"></i> {{ $user->phone }}</small></p>
                        @endif
                        @if($user->birth_date)
                            <p class="mb-1"><small class="text-muted"><i class="fas fa-birthday-cake"></i> {{ $user->birth_date->format('d.m.Y') }} ({{ $user->age }} лет)</small></p>
                        @endif
                    </div>
                </div>
            </div>

            @if($user->isStudent())
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Учебная информация</h5>
                    </div>
                    <div class="card-body">
                        @if($user->student_id)
                            <p><strong>ID студента:</strong> {{ $user->student_id }}</p>
                        @endif
                        @if($user->grade)
                            <p><strong>Класс/курс:</strong> {{ $user->grade }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-edit"></i> Редактирование профиля</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">Имя *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Фамилия *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="middle_name" class="form-label">Отчество</label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Телефон</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">О себе</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Расскажите о себе...">{{ old('bio', $user->bio) }}</textarea>
                            <div class="form-text">Краткая информация о вас (до 500 символов)</div>
                        </div>

                        <div class="mb-3">
                            <label for="birth_date" class="form-label">Дата рождения</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Сохранить изменения
                        </button>
                    </form>
                </div>
            </div>

            @if($user->isStudent())
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-chart-line"></i> Статистика обучения</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $progress = \App\Models\LevelProgress::where('student_id', $user->id)->get();
                            $completed = $progress->where('status', 'completed')->count();
                            $inProgress = $progress->where('status', 'in_progress')->count();
                            $averageScore = $progress->where('status', 'completed')->avg('score');
                        @endphp

                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="border rounded p-3">
                                    <h3 class="text-success">{{ $completed }}</h3>
                                    <p class="mb-0 text-muted">Завершено уровней</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3">
                                    <h3 class="text-primary">{{ $inProgress }}</h3>
                                    <p class="mb-0 text-muted">В процессе</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3">
                                    <h3 class="text-info">{{ round($averageScore, 1) }}</h3>
                                    <p class="mb-0 text-muted">Средний балл</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3">
                                    <h3 class="text-warning">{{ $progress->count() }}</h3>
                                    <p class="mb-0 text-muted">Всего уровней</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
