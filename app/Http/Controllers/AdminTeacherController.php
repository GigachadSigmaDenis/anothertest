<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
        $data = $this->teacherData($request);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        Teacher::create($data);

        return redirect('/admin/teachers')->with('success', 'Учитель добавлен.');
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $data = $this->teacherData($request);

        if ($request->hasFile('photo')) {
            if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
                Storage::disk('public')->delete($teacher->photo);
            }

            $data['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->update($data);

        return redirect('/admin/teachers')->with('success', 'Учитель обновлен.');
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);

        if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return back()->with('success', 'Учитель удален.');
    }

    private function teacherData(Request $request): array
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'subjects' => 'nullable|array',
            'subjects.*' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:4096',
        ]);

        $fullName = trim($validated['full_name']);
        $subjects = Teacher::normalizeSubjects($validated['subjects'] ?? []);

        return [
            'full_name' => $fullName,
            'name' => $fullName,
            'subject' => Teacher::subjectsToInline($subjects),
            'subjects' => Teacher::subjectsToText($subjects),
        ];
    }
}