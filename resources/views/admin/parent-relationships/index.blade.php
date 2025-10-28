@extends('layouts.app')

@section('title', 'Управление связями Родитель-Студент')

@section('content')
    <div class="container-fluid py-4">
        <!-- Заголовок и навигация -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Админ-панель</a></li>
                                <li class="breadcrumb-item active">Связи Родитель-Студент</li>
                            </ol>
                        </nav>
                        <h1 class="h2 mb-0">Управление связями Родитель-Студент</h1>
                    </div>
                    <div>
                        <a href="{{ route('admin.users.search') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Добавить связь
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Статистика -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2 class="mb-0">{{ $totalRelationships }}</h2>
                                <p class="mb-0">Всего связей</p>
                            </div>
                            <i class="fas fa-link fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2 class="mb-0">{{ $parentsWithChildren }}</h2>
                                <p class="mb-0">Родителей с детьми</p>
                            </div>
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2 class="mb-0">{{ $studentsWithParents }}</h2>
                                <p class="mb-0">Студентов с родителями</p>
                            </div>
                            <i class="fas fa-user-graduate fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2 class="mb-0">{{ $studentsWithoutParents }}</h2>
                                <p class="mb-0">Студентов без родителей</p>
                            </div>
                            <i class="fas fa-user-times fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Фильтры -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <x-search-form
                            :action="route('admin.parent-relationships.index')"
                            :search-value="request('parent_search')"
                            :show-role-filter="false"
                            :show-status-filter="false"
                            search-placeholder="Поиск по родителю или студенту..."
                            form-id="relationshipsSearchForm"
                        />
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="parent_search" class="form-label">Поиск по родителю</label>
                                <input type="text" class="form-control" id="parent_search" name="parent_search"
                                       value="{{ request('parent_search') }}" placeholder="Имя или email родителя...">
                            </div>
                            <div class="col-md-6">
                                <label for="student_search" class="form-label">Поиск по студенту</label>
                                <input type="text" class="form-control" id="student_search" name="student_search"
                                       value="{{ request('student_search') }}" placeholder="Имя или email студента...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Таблица связей -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Родитель</th>
                                    <th>Студент</th>
                                    <th>Тип связи</th>
                                    <th>Основной родитель</th>
                                    <th>Просмотр прогресса</th>
                                    <th>Уведомления</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($relationships as $relationship)
                                    <tr>
                                        <td>{{ $relationship->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title bg-primary rounded-circle text-white">
                                                        {{ substr($relationship->parent->first_name, 0, 1) }}{{ substr($relationship->parent->last_name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="{{ route('admin.users.show', $relationship->parent) }}" class="text-decoration-none">
                                                        {{ $relationship->parent->full_name }}
                                                    </a>
                                                    <br>
                                                    <small class="text-muted">{{ $relationship->parent->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title bg-success rounded-circle text-white">
                                                        {{ substr($relationship->student->first_name, 0, 1) }}{{ substr($relationship->student->last_name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="{{ route('admin.users.show', $relationship->student) }}" class="text-decoration-none">
                                                        {{ $relationship->student->full_name }}
                                                    </a>
                                                    <br>
                                                    <small class="text-muted">{{ $relationship->student->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $relationship->relationship_type }}</span>
                                        </td>
                                        <td>
                                        <span class="badge bg-{{ $relationship->is_primary ? 'success' : 'secondary' }}">
                                            {{ $relationship->is_primary ? 'Да' : 'Нет' }}
                                        </span>
                                        </td>
                                        <td>
                                        <span class="badge bg-{{ $relationship->can_view_progress ? 'success' : 'secondary' }}">
                                            {{ $relationship->can_view_progress ? 'Да' : 'Нет' }}
                                        </span>
                                        </td>
                                        <td>
                                        <span class="badge bg-{{ $relationship->can_receive_notifications ? 'success' : 'secondary' }}">
                                            {{ $relationship->can_receive_notifications ? 'Да' : 'Нет' }}
                                        </span>
                                        </td>
                                        <td>{{ $relationship->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editRelationshipModal"
                                                        data-relationship-id="{{ $relationship->id }}"
                                                        data-parent-id="{{ $relationship->parent_id }}"
                                                        data-student-id="{{ $relationship->student_id }}"
                                                        data-relationship-type="{{ $relationship->relationship_type }}"
                                                        data-is-primary="{{ $relationship->is_primary }}"
                                                        data-can-view-progress="{{ $relationship->can_view_progress }}"
                                                        data-can-receive-notifications="{{ $relationship->can_receive_notifications }}"
                                                        title="Редактировать связь">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.parent-relationships.destroy', $relationship) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Вы уверены, что хотите удалить эту связь?')"
                                                            title="Удалить связь">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i class="fas fa-link fa-3x text-muted mb-3"></i>
                                            <h5>Связи не найдены</h5>
                                            <p class="text-muted">Начните с добавления первой связи между родителем и студентом</p>
                                            <a href="{{ route('admin.users.search') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Добавить связь
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Пагинация -->
                        @if($relationships->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Показано с {{ $relationships->firstItem() }} по {{ $relationships->lastItem() }} из {{ $relationships->total() }} записей
                                </div>
                                {{ $relationships->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно редактирования связи -->
    <div class="modal fade" id="editRelationshipModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Редактирование связи</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editRelationshipForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_relationship_type" class="form-label">Тип связи:</label>
                                    <select class="form-select" id="edit_relationship_type" name="relationship_type">
                                        <option value="parent">Родитель</option>
                                        <option value="guardian">Опекун</option>
                                        <option value="relative">Родственник</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" id="edit_is_primary" name="is_primary" value="1">
                                        <label class="form-check-label" for="edit_is_primary">Основной родитель</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit_can_view_progress" name="can_view_progress" value="1">
                                    <label class="form-check-label" for="edit_can_view_progress">Может просматривать прогресс</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit_can_receive_notifications" name="can_receive_notifications" value="1">
                                    <label class="form-check-label" for="edit_can_receive_notifications">Может получать уведомления</label>
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
            // Обработка модального окна редактирования связи
            const editModal = document.getElementById('editRelationshipModal');
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const relationshipId = button.getAttribute('data-relationship-id');
                const relationshipType = button.getAttribute('data-relationship-type');
                const isPrimary = button.getAttribute('data-is-primary') === '1';
                const canViewProgress = button.getAttribute('data-can-view-progress') === '1';
                const canReceiveNotifications = button.getAttribute('data-can-receive-notifications') === '1';

                // Устанавливаем значения формы
                document.getElementById('edit_relationship_type').value = relationshipType;
                document.getElementById('edit_is_primary').checked = isPrimary;
                document.getElementById('edit_can_view_progress').checked = canViewProgress;
                document.getElementById('edit_can_receive_notifications').checked = canReceiveNotifications;

                // Устанавливаем action формы
                document.getElementById('editRelationshipForm').action = `/admin/parent-relationships/${relationshipId}`;
            });
        });
    </script>
@endpush
