<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    /**
     * Форма авторизации
     *
     * GET /login
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Авторизация
     *
     * POST /login
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->post('remember'))) {
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
    public function destroy(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
