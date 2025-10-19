@extends('layouts.app')

@section('title', 'Создание курса - Панель учителя')

@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Панель учителя</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.index') }}">Мои курсы</a></li>
                    <li class="breadcrumb-item active">Создание курса</li>
                </ol>
            </nav>

            <h1><i class="fas fa-plus-circle"></i> Создание нового курса</h1>
            <p class="lead">Заполните информацию о новом учебном курсе</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Основная информация</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.courses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="curriculum_id" class="form-label">Учебный план *</label>
                            <select class="form-select @error('curriculum_id') is-invalid @enderror"
                                    id="curriculum_id" name="curriculum_id" required>
                                <option value="">-- Выберите учебный план --</option>
                                @foreach($curricula as $curriculum)
                                    <option value="{{ $curriculum->id }}"
                                        {{ old('curriculum_id') == $curriculum->id ? 'selected' : '' }}>
                                        {{ $curriculum->subject->name }} - {{ $curriculum->level->name }}
                                        ({{ $curriculum->total_hours }} часов)
                                    </option>
                                @endforeach
                            </select>
                            @error('curriculum_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Выберите предмет и уровень для вашего курса
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Название курса *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title') }}"
                                   placeholder="Введите название курса" required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Описание курса *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4"
                                      placeholder="Опишите содержание курса, цели обучения и требования к студентам..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Подробное описание поможет студентам понять, чему они научатся на курсе
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение курса</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/*">
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Рекомендуемый размер: 1200x600px. Форматы: JPG, PNG, GIF
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Важная информация</h6>
                            <ul class="mb-0">
                                <li>После создания курс будет отправлен на модерацию администратору</li>
                                <li>Вы сможете добавлять уроки после одобрения курса</li>
                                <li>Убедитесь, что описание курса понятно и информативно</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.courses.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Отмена
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Создать курс
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Информация о создании курса -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Советы по созданию курса</h5>
                </div>
                <div class="card-body">
                    <div class="tips-list">
                        <div class="tip-item mb-3">
                            <h6><i class="fas fa-check text-success"></i> Четкое название</h6>
                            <p class="small text-muted mb-0">
                                Название должно точно отражать содержание курса
                            </p>
                        </div>
                        <div class="tip-item mb-3">
                            <h6><i class="fas fa-check text-success"></i> Подробное описание</h6>
                            <p class="small text-muted mb-0">
                                Опишите чему научатся студенты и какие знания получат
                            </p>
                        </div>
                        <div class="tip-item mb-3">
                            <h6><i class="fas fa-check text-success"></i> Соответствие уровню</h6>
                            <p class="small text-muted mb-0">
                                Убедитесь, что содержание соответствует выбранному уровню сложности
                            </p>
                        </div>
                        <div class="tip-item">
                            <h6><i class="fas fa-check text-success"></i> Структура обучения</h6>
                            <p class="small text-muted mb-0">
                                Продумайте последовательность уроков и практических заданий
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Статистика доступных учебных планов -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Доступные учебные планы</h5>
                </div>
                <div class="card-body">
                    @php
                        $subjectsCount = $curricula->unique('subject_id')->count();
                        $levelsCount = $curricula->unique('level_id')->count();
                    @endphp
                    <div class="text-center">
                        <div class="row">
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <h4 class="text-primary mb-0">{{ $subjectsCount }}</h4>
                                    <small class="text-muted">Предметов</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <h4 class="text-success mb-0">{{ $levelsCount }}</h4>
                                    <small class="text-muted">Уровней</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
