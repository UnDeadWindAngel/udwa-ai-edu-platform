@extends('layouts.app')

@section('title', 'Редактирование курса - ' . $course->title)

@section('content')
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Панель учителя</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.index') }}">Мои курсы</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.courses.show', $course) }}">{{ $course->title }}</a></li>
                    <li class="breadcrumb-item active">Редактирование</li>
                </ol>
            </nav>

            <h1><i class="fas fa-edit"></i> Редактирование курса: {{ $course->title }}</h1>
            <p class="lead">Обновите информацию о вашем учебном курсе</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Основная информация</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Название курса *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $course->title) }}"
                                   placeholder="Введите название курса" required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Описание курса *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="5"
                                      placeholder="Опишите содержание курса, цели обучения и требования к студентам..."
                                      required>{{ old('description', $course->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Подробное описание поможет студентам понять, чему они научатся на курсе
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение курса</label>

                            @if($course->image)
                                <div class="mb-2">
                                    <strong>Текущее изображение:</strong>
                                    <div class="mt-1">
                                        <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image"
                                             class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                </div>
                            @endif

                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/*">
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Оставьте поле пустым, чтобы сохранить текущее изображение. Рекомендуемый размер: 1200x600px
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                    {{ old('is_active', $course->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Активный курс
                                </label>
                            </div>
                            <div class="form-text">
                                Если выключено, курс будет скрыт от студентов
                            </div>
                        </div>

                        @if(!$course->is_approved)
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-exclamation-triangle"></i> Курс на модерации</h6>
                                <p class="mb-0">Ваш курс ожидает проверки администратором. После одобрения вы сможете добавлять уроки.</p>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Отмена
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Информация о курсе -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Информация о курсе</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Предмет:</strong></td>
                            <td>
                                <i class="{{ $course->curriculum->subject->icon }}"
                                   style="color: {{ $course->curriculum->subject->color }}"></i>
                                {{ $course->curriculum->subject->name }}
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Уровень:</strong></td>
                            <td>{{ $course->curriculum->level->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Статус модерации:</strong></td>
                            <td>
                                @if($course->is_approved)
                                    <span class="badge bg-success">Одобрен</span>
                                @else
                                    <span class="badge bg-warning">На модерации</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Дата создания:</strong></td>
                            <td>{{ $course->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Последнее обновление:</strong></td>
                            <td>{{ $course->updated_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Количество уроков:</strong></td>
                            <td>{{ $course->lessons->count() }}</td>
                        </tr>
                        <tr>
                            <td><strong>Количество студентов:</strong></td>
                            <td>{{ $course->enrollments->count() }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Действия -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> Действия</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i> Просмотр курса
                        </a>

                        @if($course->is_approved && $course->lessons->count() > 0)
                            <button class="btn btn-outline-success" disabled>
                                <i class="fas fa-plus"></i> Добавить урок
                            </button>
                        @endif

                        <form action="{{ route('teacher.courses.destroy', $course) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger"
                                    onclick="return confirm('Вы уверены, что хотите удалить этот курс? Это действие нельзя отменить.')">
                                <i class="fas fa-trash"></i> Удалить курс
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Предупреждения -->
            @if($course->enrollments->count() > 0)
                <div class="card mt-3 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Внимание</h5>
                    </div>
                    <div class="card-body">
                        <p class="small mb-0">
                            На этом курсе обучаются {{ $course->enrollments->count() }} студентов.
                            Внесение изменений может повлиять на их обучение.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
