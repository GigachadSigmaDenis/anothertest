<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class AdminController extends Controller
{
    public function loginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $login = $request->login;
        $password = $request->password;

        if ($login === 'admin' && $password === '12фвьшт34') {
            session(['admin' => true]);

            return redirect('/admin/dashboard');
        }

        return back()->with('error', 'Неверный логин или пароль');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function news()
    {
        return view('admin.news');
    }
}