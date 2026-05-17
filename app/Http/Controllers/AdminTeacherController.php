<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminTeacherController extends Controller
{
    public function index(Request $request)
    {
        $teachers = Teacher::latest()->get();

        $editTeacher = null;

        if ($request->filled('edit')) {
            $editTeacher = Teacher::find($request->edit);
        }

        return view('admin.teachers.index', compact('teachers', 'editTeacher'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required',
            'subjects' => 'required',
            'photo' => 'nullable|image'
        ]);

        // Заполняем старые обязательные поля таблицы
        $nameParts = explode(' ', trim($data['full_name']));

        $data['surname'] = $nameParts[0] ?? $data['full_name'];
        $data['name'] = $nameParts[1] ?? $data['full_name'];
        $data['subject'] = $data['subjects'];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        Teacher::create($data);

        return redirect('/admin/teachers')->with('success', 'Учитель успешно добавлен');
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $data = $request->validate([
            'full_name' => 'required',
            'subjects' => 'required',
            'photo' => 'nullable|image'
        ]);

        // Обновляем старые обязательные поля таблицы
        $nameParts = explode(' ', trim($data['full_name']));

        $data['surname'] = $nameParts[0] ?? $data['full_name'];
        $data['name'] = $nameParts[1] ?? $data['full_name'];
        $data['subject'] = $data['subjects'];

        if ($request->hasFile('photo')) {
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }

            $data['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->update($data);

        return redirect('/admin/teachers')->with('success', 'Данные учителя обновлены');
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);

        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return redirect('/admin/teachers')->with('success', 'Учитель удалён');
    }
}