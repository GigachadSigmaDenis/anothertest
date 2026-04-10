<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        
        $query = User::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('login', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderBy('role')
                      ->orderBy('full_name')
                      ->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }
    
    public function updateRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:guest,student,teacher'
        ]);
        
        $user = User::findOrFail($request->user_id);
        
        if ($user->role === 'admin') {
            return back()->with('error', 'Нельзя изменить роль администратора');
        }
        
        $oldRole = $user->role;
        $user->role = $request->role;
        
        if ($request->role !== 'student') {
            $user->studend_class = 'none';
        } else {
            if (empty($user->studend_class) || $user->studend_class === 'none') {
                $user->studend_class = '1';
            }
        }
        
        $user->save();
        
        return redirect()->back()->with('success', 'Роль успешно обновлена');
    }
    
    public function updateClass(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'class' => 'required|string'
        ]);
        
        $user = User::findOrFail($request->user_id);
        
        if ($request->class === 'none') {
            $user->studend_class = 'none';
            if ($user->role === 'student') {
                $user->role = 'guest';
            }
        } else {
            $user->studend_class = $request->class;
            if ($user->role !== 'teacher' && $user->role !== 'admin') {
                $user->role = 'student';
            }
        }
        
        $user->save();
        
        return redirect()->back()->with('success', 'Класс успешно обновлен');
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role === 'admin') {
            return back()->with('error', 'Нельзя удалить администратора');
        }
        
        $user->delete();
        
        return back()->with('success', 'Пользователь успешно удален');
    }
}