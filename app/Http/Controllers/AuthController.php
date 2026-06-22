<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'login' => 'required|string|max:255|unique:users,login',
            'full_name' => ['required', 'string', 'max:255', 'regex:/^(?=.*[А-Яа-яЁё])[А-Яа-яЁё\s]+$/u'],
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:4',
        ], [
            'login.required' => 'Введите логин.',
            'login.unique' => 'Такой логин уже занят.',
            'full_name.required' => 'Введите ФИО.',
            'full_name.regex' => 'ФИО можно вводить только кириллицей и пробелами.',
            'email.email' => 'Введите корректную почту или оставьте поле пустым.',
            'email.unique' => 'Такая почта уже используется.',
            'password.required' => 'Введите пароль.',
            'password.min' => 'Пароль должен быть не короче 4 символов.',
        ]);

        $data['full_name'] = preg_replace('/\s+/', ' ', trim($data['full_name']));
        $data['email'] = trim((string) ($data['email'] ?? '')) ?: null;
        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'guest';

        User::create($data);

        return redirect('/login')->with('success', 'Регистрация выполнена. Теперь можно войти.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = [
            'login' => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            return redirect('/');
        }

        return back()->with('error', 'Неверный логин или пароль')->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
