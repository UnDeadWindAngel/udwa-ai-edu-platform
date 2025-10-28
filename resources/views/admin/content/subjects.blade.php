@extends('layouts.app')

@section('title', 'Управление предметами')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1>Управление предметами</h1>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                        <i class="fas fa-plus"></i> Добавить предмет
                    </button>
                </div>
            </div>
        </div>

        <!-- Статистика предметов -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2 class="mb-0">{{ $totalSubjects }}</h2>
                                <p class="mb-0">Всего предметов</p>
                            </div>
                            <i class="fas fa-book fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2 class="mb-0">{{ $activeSubjects }}</h2>
                                <p class="mb-0">Активных</p>
                            </div>
                            <i class="fas fa-check-circle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2 class="mb-0">{{ $totalCourses }}</h2>
                                <p class="mb-0">Курсов</p>
                            </div>
                            <i class="fas fa-graduation-cap fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2 class="mb-0">{{ $totalLevels }}</h2>
                                <p class="mb-0">Уровней</p>
                            </div>
                            <i class="fas fa-layer-group fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Таблица предметов -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Описание</th>
                                    <th>Иконка</th>
                                    <th>Цвет</th>
                                    <th>Порядок</th>
                                    <th>Статус</th>
                                    <th>Курсов</th>
                                    <th>Уровней</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($subjects as $subject)
                                    <tr>
                                        <td>{{ $subject->id }}</td>
                                        <td>
                                            <h6 class="mb-0">{{ $subject->name }}</h6>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($subject->description, 50) }}</small>
                                        </td>
                                        <td>
                                            <i class="{{ $subject->icon }} fa-lg" style="color: {{ $subject->color }}"></i>
                                        </td>
                                        <td>
                                        <span class="badge" style="background-color: {{ $subject->color }}; color: white">
                                            {{ $subject->color }}
                                        </span>
                                        </td>
                                        <td>{{ $subject->order }}</td>
                                        <td>
                                        <span class="badge bg-{{ $subject->is_active ? 'success' : 'secondary' }}">
                                            {{ $subject->is_active ? 'Активен' : 'Неактивен' }}
                                        </span>
                                        </td>
                                        <td>{{ $subject->courses_count }}</td>
                                        <td>{{ $subject->levels_count }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('subjects.show', $subject) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editSubjectModal"
                                                        data-subject-id="{{ $subject->id }}"
                                                        data-subject-name="{{ $subject->name }}"
                                                        data-subject-description="{{ $subject->description }}"
                                                        data-subject-icon="{{ $subject->icon }}"
                                                        data-subject-color="{{ $subject->color }}"
                                                        data-subject-order="{{ $subject->order }}"
                                                        data-subject-active="{{ $subject->is_active }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @if($subject->is_active)
                                                    <form action="{{ route('admin.content.subjects.deactivate', $subject) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                            <i class="fas fa-pause"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.content.subjects.activate', $subject) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                            <h5>Предметы не найдены</h5>
                                            <p class="text-muted">Добавьте первый предмет для начала работы</p>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Пагинация -->
                        @if($subjects->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Показано с {{ $subjects->firstItem() }} по {{ $subjects->lastItem() }} из {{ $subjects->total() }} записей
                                </div>
                                {{ $subjects->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно создания предмета -->
    <div class="modal fade" id="createSubjectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Добавить предмет</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.content.subjects.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Название предмета *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Описание</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="icon" class="form-label">Иконка</label>
                                    <input type="text" class="form-control" id="icon" name="icon"
                                           placeholder="fas fa-book" value="fas fa-book">
                                    <small class="form-text text-muted">Font Awesome класс иконки</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="color" class="form-label">Цвет</label>
                                    <input type="color" class="form-control" id="color" name="color" value="#007bff">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="order" class="form-label">Порядок</label>
                                    <input type="number" class="form-control" id="order" name="order" value="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                        <label class="form-check-label" for="is_active">Активный</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Создать предмет</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Модальное окно редактирования предмета -->
    <div class="modal fade" id="editSubjectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Редактировать предмет</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editSubjectForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Название предмета *</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Описание</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_icon" class="form-label">Иконка</label>
                                    <input type="text" class="form-control" id="edit_icon" name="icon">
                                    <small class="form-text text-muted">Font Awesome класс иконки</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_color" class="form-label">Цвет</label>
                                    <input type="color" class="form-control" id="edit_color" name="color">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_order" class="form-label">Порядок</label>
                                    <input type="number" class="form-control" id="edit_order" name="order">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1">
                                        <label class="form-check-label" for="edit_is_active">Активный</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Обработка модального окна редактирования
            const editModal = document.getElementById('editSubjectModal');
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const subjectId = button.getAttribute('data-subject-id');
                const subjectName = button.getAttribute('data-subject-name');
                const subjectDescription = button.getAttribute('data-subject-description');
                const subjectIcon = button.getAttribute('data-subject-icon');
                const subjectColor = button.getAttribute('data-subject-color');
                const subjectOrder = button.getAttribute('data-subject-order');
                const subjectActive = button.getAttribute('data-subject-active');

                // Обновляем форму
                document.getElementById('edit_name').value = subjectName;
                document.getElementById('edit_description').value = subjectDescription;
                document.getElementById('edit_icon').value = subjectIcon;
                document.getElementById('edit_color').value = subjectColor;
                document.getElementById('edit_order').value = subjectOrder;
                document.getElementById('edit_is_active').checked = subjectActive === '1';

                // Обновляем action формы
                document.getElementById('editSubjectForm').action = `/admin/subjects/${subjectId}`;
            });
        });
    </script>
@endpush
