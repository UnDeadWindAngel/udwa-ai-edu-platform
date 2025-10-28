@extends('layouts.app')

@section('title', 'Управление ролями')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1>Управление ролями</h1>
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Добавить роль
                    </a>
                </div>
            </div>
        </div>

        <!-- Таблица ролей -->
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
                                    <th>Отображаемое имя</th>
                                    <th>Описание</th>
                                    <th>Пользователей</th>
                                    <th>Разрешений</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td>
                                            <strong>{{ $role->name }}</strong>
                                        </td>
                                        <td>{{ $role->display_name }}</td>
                                        <td>
                                            <small class="text-muted">{{ $role->description ?? '—' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $role->users_count }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $role->permissions_count }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.roles.edit', $role) }}"
                                                   class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($role->users_count === 0)
                                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST"
                                                          onsubmit="return confirm('Вы уверены, что хотите удалить эту роль?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-sm btn-outline-secondary" disabled
                                                            title="Невозможно удалить роль с пользователями">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-user-tag fa-3x text-muted mb-3"></i>
                                            <h5>Роли не найдены</h5>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Пагинация -->
                        @if($roles->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Показано с {{ $roles->firstItem() }} по {{ $roles->lastItem() }} из {{ $roles->total() }} записей
                                </div>
                                {{ $roles->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
