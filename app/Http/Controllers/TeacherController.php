<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereNotIn('role', ['teacher', 'admin']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('login', 'like', '%' . $request->search . '%')
                  ->orWhere('full_name', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('full_name')->get();

        return view('teacher.classes', compact('users'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'class' => 'required|string'
        ]);

        $user = User::findOrFail($request->user_id);

        if ($user->role === 'teacher') {
            return back()->with('error', 'Нельзя изменить класс учителя');
        }

        if ($user->role === 'admin') {
            return back()->with('error', 'Нельзя изменить класс администратора');
        }

        if ($request->class === 'none') {
            $user->role = 'guest';
            $user->studend_class = 'none';
        } else {
            $user->role = 'student';
            $user->studend_class = $request->class;
        }

        try {
            $user->save();
            return back()->with('success', 'Класс успешно обновлен');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при сохранении: ' . $e->getMessage());
        }
    }
}