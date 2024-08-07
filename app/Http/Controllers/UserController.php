<?php

namespace App\Http\Controllers;

use App\Helpers\Navigation;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests\User\UserLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Форма авторизации
     *
     * GET /login
     */
    public function login()
    {
        return Inertia::render('User/Login');
    }

    /**
     * Авторизация
     *
     * POST /login
     */
    public function loginPost(UserLoginRequest $request)
    {
        if (Auth::attempt($request->validated(), $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended();
        }

        return back()->withErrors([
            'password' => 'Неверный пароль',
        ])->onlyInput('email');
    }

    /**
     * Выход из профиля
     *
     * POST /logout
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }

    /**
     * Список пользователей
     *
     * GET /users
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAll', User::class);

        $users = User::query()
            // Фильтрация через scopeFilter
            ->filter($request->only(['role', 'q']))
            ->orderBy('id', 'asc')
            ->paginate(5)
            // Добавляем параметры запроса к постраничной навигации
            ->appends($request->only(['role', 'q']))
            ->through(fn($user) => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'is_blocked' => $user->is_blocked,
                'created_at' => $user->created_at,
            ]);

        return Inertia::render('User/Index', [
            'users' => $users,
            'userRoles' => config('models.users.roles'),
            'initialFilter' => $request->only(['role', 'q']),
        ]);
    }

    /**
     * Страница пользователя
     *
     * GET /users/{username}
     */
    public function show(User $user)
    {
        Gate::authorize('view', $user);

        return Inertia::render('User/Show', [
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'first_name' => $user->first_name,
                'email' => $user->email,
                'role' => $user->role,
                'is_blocked' => $user->is_blocked,
                'created_at' => $user->created_at,
            ],
            'userRoles' => config('models.users.roles'),
            'subheaderTitle' => '@' . $user->username,
            'menuLinks' => Navigation::getLinks('user'),
        ]);
    }

    /**
     * Форма создания пользователя
     *
     * GET /users/create
     */
    public function create()
    {
        Gate::authorize('create', User::class);

        return Inertia::render('User/Create', [
            'userRoles' => config('models.users.roles'),
        ]);
    }

    /**
     * Сохранение созданного пользователя
     *
     * POST /users
     */
    public function store(UserStoreRequest $request)
    {
        Gate::authorize('create', User::class);
        User::create($request->validated());

        return Redirect::route('users.index');
    }

    /**
     * Форма редактирования пользователя
     *
     * GET /users/{username}/edit
     */
    public function edit(User $user)
    {
        Gate::authorize('update', $user);

        return Inertia::render('User/Edit', [
            'id' => $user->id,
            'values' => [
                'username' => $user->username,
                'first_name' => $user->first_name,
                'email' => $user->email,
                'role' => $user->role,
                'is_blocked' => $user->is_blocked,
                'created_at' => $user->created_at,
            ],
            'userRoles' => config('models.users.roles'),
            'canEditAll' => request()->user()->can('updateAll', $user),
            'subheaderTitle' => '@' . $user->username,
            'menuLinks' => Navigation::getLinks('user'),
        ]);
    }

    /**
     * Сохранение редактируемого пользователя
     *
     * PUT /users/{username}
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        Gate::authorize('update', $user);
        $user->update($request->validated());

        return Redirect::route('users.show', [
            'user' => $user->username,
        ]);
    }

    /**
     * Удаление пользователя
     *
     * DELETE /users/{username}
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        $user->delete();

        return back();
    }
}
