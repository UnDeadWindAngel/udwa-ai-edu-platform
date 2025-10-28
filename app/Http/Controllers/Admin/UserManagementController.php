<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $query = User::with('roles');

        // Применяем фильтры если они есть
        $this->applyFilters($query);

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        $user->load(['roles', 'levelProgress.subject', 'enrollments.course']);
        $roles = Role::all();

        return view('admin.users.show', compact('user', 'roles'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'student_id' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Пользователь успешно обновлен.');
    }

    public function destroy(User $user)
    {
        // Не позволяем удалить самого себя
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Вы не можете удалить свой собственный аккаунт.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно удален.');
    }

    public function editRoles(User $user)
    {
        $roles = Role::all();
        $user->load('roles');

        return view('admin.users.edit-roles', compact('user', 'roles'));
    }

    public function updateRoles(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        try {
            // Не позволяем убрать все роли у пользователя
            if (empty($validated['roles'])) {
                return back()->with('error', 'Пользователь должен иметь хотя бы одну роль.');
            }

            // Не позволяем убрать роль администратора у самого себя
            if ($user->id === auth()->id() && !in_array(Role::where('name', 'admin')->first()->id, $validated['roles'])) {
                return back()->with('error', 'Вы не можете убрать у себя роль администратора.');
            }

            $user->roles()->sync($validated['roles']);

            return redirect()->route('admin.users.show', $user)
                ->with('success', 'Роли пользователя успешно обновлены.');

        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при обновлении ролей пользователя.');
        }
    }

    public function rolesIndex()
    {
        $roles = Role::with(['users', 'permissions'])
            ->withCount(['users', 'permissions'])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.roles.index', compact('roles'));
    }

    public function rolesCreate()
    {
        $permissions = Permission::orderBy('name')->get();
        return view('admin.roles.create', compact('permissions'));
    }

    public function rolesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            $role = Role::create($validated);

            if (isset($validated['permissions'])) {
                $role->permissions()->sync($validated['permissions']);
            }

            return redirect()->route('admin.roles.index')
                ->with('success', 'Роль успешно создана.');

        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при создании роли.');
        }
    }

    public function rolesEdit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $role->load('permissions');

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function rolesUpdate(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            $role->update($validated);

            $role->permissions()->sync($validated['permissions'] ?? []);

            return redirect()->route('admin.roles.index')
                ->with('success', 'Роль успешно обновлена.');

        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при обновлении роли.');
        }
    }

    public function rolesDestroy(Role $role)
    {
        try {
            // Проверяем, используется ли роль
            if ($role->users_count > 0) {
                return back()->with('error', 'Невозможно удалить роль, так как она назначена пользователям.');
            }

            $role->delete();

            return redirect()->route('admin.roles.index')
                ->with('success', 'Роль успешно удалена.');

        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при удалении роли.');
        }
    }

    public function toggleStatus(User $user)
    {
        try {
            $user->update(['is_active' => !$user->is_active]);

            $status = $user->is_active ? 'активирован' : 'деактивирован';

            return back()->with('success', "Пользователь {$user->full_name} успешно {$status}.");

        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при изменении статуса пользователя.');
        }
    }

    public function activate(User $user)
    {
        try {
            $user->update(['is_active' => true]);
            return back()->with('success', "Пользователь {$user->full_name} успешно активирован.");
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при активации пользователя.');
        }
    }

    public function deactivate(User $user)
    {
        try {
            // Не позволяем деактивировать самого себя
            if ($user->id === auth()->id()) {
                return back()->with('error', 'Вы не можете деактивировать свой собственный аккаунт.');
            }

            $user->update(['is_active' => false]);
            return back()->with('success', "Пользователь {$user->full_name} успешно деактивирован.");
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при деактивации пользователя.');
        }
    }

    public function search(Request $request)
    {
        $query = User::with('roles');

        // Применяем расширенные фильтры
        $this->applyAdvancedFilters($query, $request);

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $roles = Role::all();

        return view('admin.users.search', compact('users', 'roles'));
    }

    public function searchParents(Request $request)
    {
        $query = User::whereHas('roles', function($q) {
            $q->where('name', 'parent');
        })->where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $parents = $query->select('id', 'first_name', 'last_name', 'email')
            ->limit(10)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'email' => $user->email
                ];
            });

        return response()->json($parents);
    }

    private function applyFilters($query)
    {
        // Поиск по имени, фамилии, email
        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Фильтрация по роли
        if (request()->filled('role')) {
            $query->whereHas('roles', function($q) {
                $q->where('name', request('role'));
            });
        }

        // Фильтрация по статусу
        if (request()->filled('status')) {
            $query->where('is_active', request('status') === 'active');
        }
    }

    private function applyAdvancedFilters($query, $request)
    {
        // Расширенный поиск
        if ($request->filled('search')) {
            $search = $request->search;

            if ($request->filled('exact_match')) {
                // Точное совпадение
                $query->where(function($q) use ($search) {
                    $q->where('first_name', $search)
                        ->orWhere('last_name', $search)
                        ->orWhere('email', $search)
                        ->orWhere('student_id', $search);
                });
            } else {
                // Частичное совпадение
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('student_id', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }
        }

        // Фильтрация по роли
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Фильтрация по статусу
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Включая неактивных пользователей
        if (!$request->filled('include_inactive')) {
            $query->where('is_active', true);
        }
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'super-admin')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => 'nullable|date',
            'student_id' => 'nullable|string|max:50|unique:users',
            'grade' => 'nullable|string|max:50',
            'bio' => 'nullable|string|max:1000',
            'registration_address' => 'nullable|string|max:500',
            'emergency_contacts' => 'nullable|string|max:1000',
            'role' => 'required|string|exists:roles,name',
            'is_active' => 'boolean',
            'send_welcome_email' => 'boolean',
            'require_password_change' => 'boolean',
        ]);

        try {
            // Создаем пользователя
            $userData = $request->only([
                'first_name', 'last_name', 'middle_name', 'email', 'phone',
                'birth_date', 'student_id', 'grade', 'bio', 'registration_address'
            ]);

            $userData['password'] = Hash::make($request->password);
            $userData['is_active'] = $request->has('is_active');

            if ($request->has('emergency_contacts')) {
                $userData['emergency_contacts'] = json_encode(explode("\n", $request->emergency_contacts));
            }

            $user = User::create($userData);

            // Назначаем роль
            $user->roles()->attach(Role::where('name', $request->role)->first());

            // Дополнительные действия
            if ($request->has('send_welcome_email')) {
                // Отправка приветственного письма
                // Mail::to($user->email)->send(new WelcomeEmail($user, $request->password));
            }

            if ($request->has('require_password_change')) {
                // Установка флага смены пароля
                $user->update(['force_password_change' => true]);
            }

            return redirect()->route('admin.users.show', $user)
                ->with('success', "Пользователь {$user->full_name} успешно создан.");

        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при создании пользователя: ' . $e->getMessage())
                ->withInput();
        }
    }
}
