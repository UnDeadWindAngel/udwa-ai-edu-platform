@props([
    'action' => route('admin.users.index'),
    'method' => 'GET',
    'searchValue' => request('search'),
    'roleValue' => request('role'),
    'statusValue' => request('status'),
    'showRoleFilter' => true,
    'showStatusFilter' => true,
    'roles' => collect(),
    'searchPlaceholder' => 'Поиск по имени или email...',
    'formId' => 'searchForm'
])

<form action="{{ $action }}" method="{{ $method }}" id="{{ $formId }}" class="row g-3">
    <div class="col-md-4">
        <label for="search" class="form-label">Поиск</label>
        <input type="text" class="form-control" id="search" name="search"
               value="{{ $searchValue }}" placeholder="{{ $searchPlaceholder }}">
    </div>

    @if($showRoleFilter)
        <div class="col-md-3">
            <label for="role" class="form-label">Роль</label>
            <select class="form-select" id="role" name="role">
                <option value="">Все роли</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ $roleValue == $role->name ? 'selected' : '' }}>
                        {{ $role->display_name ?? $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    @if($showStatusFilter)
        <div class="col-md-3">
            <label for="status" class="form-label">Статус</label>
            <select class="form-select" id="status" name="status">
                <option value="">Все статусы</option>
                <option value="active" {{ $statusValue == 'active' ? 'selected' : '' }}>Активные</option>
                <option value="inactive" {{ $statusValue == 'inactive' ? 'selected' : '' }}>Неактивные</option>
            </select>
        </div>
    @endif

    <div class="col-md-2 d-flex align-items-end">
        <div class="d-grid gap-2 w-100">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Поиск
            </button>
            @if(request()->hasAny(['search', 'role', 'status']))
                <a href="{{ $action }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Сбросить
                </a>
            @endif
        </div>
    </div>
</form>
