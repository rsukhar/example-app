<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Список пользователей
     *
     * GET /users
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAll', User::class);

        // Получение данных из модели без вспомогательных методов в контроллере по возможности (нет сложной логики)
        $users = User::query()
            // Фильтрация через scopeFilter
            ->filter($request->only(['role', 'q']))
            ->orderBy('id', 'asc')
            // Пагинация через системный метод
            ->paginate(5)
            // Добавление параметров запроса к пагинации
            ->appends($request->only(['role', 'q']))
            // Выборка только нужных полей для данной страницы
            ->through(fn($user) => [
                'id' => $user->id,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'role_name' => config('models.users.roles.' . $user->role, $user->role),
                'is_blocked' => $user->is_blocked,
            ]);

        return Inertia::render('User/Index', [
            'users' => $users,
            'userRoles' => User::getUserRoles(),
            'initialFilter' => $request->only(['role', 'q']),
        ]);
    }

    /**
     * Страница пользователя
     *
     * GET /users/{username}
     */
    public function show(Request $request, string $username): Response
    {
        $user = User::where('username', $username)->firstOrFail();
        // Авторизация через политику
        Gate::authorize('view', $user);

        // Выборка только нужных полей для данной страницы
        return Inertia::render('User/Show', [
            'user' => collect($user)
                ->only(['created_at', 'email', 'username', 'name', 'role', 'birthday', 'is_blocked'])
                ->put('role_name', config('models.users.roles.' . $user->role))
        ]);
    }

    /**
     * Форма создания пользователя
     *
     * GET /users/create
     */
    public function create(Request $request): Response
    {
        // Авторизация через политику
        Gate::authorize('create', User::class);

        return Inertia::render('User/Create', [
            'userRoles' => User::getUserRoles(false),
        ]);
    }

    /**
     * Сохранение созданного пользователя
     *
     * POST /users
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        // Авторизация через политику
        Gate::authorize('create', User::class);
        // Запись данных без вспомогательных методов в контроллере по возможности (нет сложной логики)
        // Если присутствует сложная обработка/логика, то выносим в (new User())->storeData($request->validated());
        (new User())->fill($request->validated())
            ->save();

        return Redirect::route('users.index');
    }

    /**
     * Форма редактирования пользователя
     *
     * GET /users/{username}/edit
     */
    public function edit(Request $request, string $username): Response
    {
        $user = User::where('username', $username)->firstOrFail();
        // Авторизация через политику
        Gate::authorize('update', $user);

        // Выборка только нужных полей для данной страницы
        return Inertia::render('User/Edit', [
            'user' => collect($user)->only(['id', 'username', 'email', 'name', 'role', 'birthday', 'is_blocked']),
            'userRoles' => User::getUserRoles(),
        ]);
    }

    /**
     * Сохранение редактируемого пользователя
     *
     * PUT /users/{username}
     */
    public function update(UpdateRequest $request, string $username): RedirectResponse
    {
        $user = User::where('username', $username)->firstOrFail();
        // Авторизация через политику
        Gate::authorize('update', $user);
        // Обновление данных без вспомогательных методов в контроллере по возможности (нет сложной логики)
        // Если присутствует сложная обработка/логика, то выносим в $user->updateData($request->validated());
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
    public function destroy(Request $request, string $username): RedirectResponse
    {
        $user = User::where('username', $username)->firstOrFail();
        // Авторизация через политику
        Gate::authorize('delete', $user);
        // Удаление данных без вспомогательных методов в контроллере по возможности (нет сложной логики)
        // Если присутствует сложная обработка/логика, то выносим в $user->deleteData($request->validated());
        // Для зависящих друг от друга запросов используем DB::transaction()
        $result = $user->delete();

        return ($result) ? back() : back()->withErrors([
            'message' => 'Ошибка при удалении пользователя',
        ]);
    }
}
