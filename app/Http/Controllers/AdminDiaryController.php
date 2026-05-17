<?php

namespace App\Http\Controllers;

use App\Models\DiaryAssignment;
use App\Models\DiaryGrade;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDiaryController extends Controller
{
    public function index(Request $request)
    {
        $days = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница'];

        $class = $request->get('class', '5');

        $weekStart = $request->filled('week_start')
            ? Carbon::parse($request->week_start)->startOfWeek(Carbon::MONDAY)
            : now()->startOfWeek(Carbon::MONDAY);

        $selectedDay = $request->get('day', $days[0]);

        $lessons = Schedule::where('class', $class)
            ->whereDate('week_start_date', $weekStart->format('Y-m-d'))
            ->where('day', $selectedDay)
            ->orderBy('lesson_number')
            ->get();

        $assignments = DiaryAssignment::with(['links', 'files', 'grades'])
            ->where('class', $class)
            ->whereDate('week_start_date', $weekStart->format('Y-m-d'))
            ->where('day', $selectedDay)
            ->get();

        $students = User::where('role', 'student')
            ->where('studend_class', $class)
            ->orderBy('full_name')
            ->get();

        $classes = User::where('role', 'student')
            ->where('studend_class', '!=', 'none')
            ->select('studend_class')
            ->distinct()
            ->orderBy('studend_class')
            ->pluck('studend_class');

        return view('admin.diary.index', compact(
            'days',
            'class',
            'classes',
            'weekStart',
            'selectedDay',
            'lessons',
            'assignments',
            'students'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class' => 'required|string',
            'week_start_date' => 'required|date',
            'day' => 'required|string',
            'lesson_number' => 'required|integer',
            'subject' => 'required|string',
            'text' => 'nullable|string',
            'links' => 'nullable|array',
            'links.*.title' => 'nullable|string|max:255',
            'links.*.url' => 'nullable|url|max:700',
            'files.*' => 'nullable|file|max:20480',
            'grades' => 'nullable|array',
        ]);

        $weekStart = Carbon::parse($request->week_start_date)
            ->startOfWeek(Carbon::MONDAY)
            ->format('Y-m-d');

        $assignment = DiaryAssignment::updateOrCreate(
            [
                'class' => $request->class,
                'week_start_date' => $weekStart,
                'day' => $request->day,
                'lesson_number' => $request->lesson_number,
                'subject' => $request->subject,
            ],
            [
                'text' => $request->text,
                'created_by_id' => auth()->id(),
            ]
        );

        $assignment->links()->delete();

        foreach ($request->links ?? [] as $link) {
            if (!empty($link['url'])) {
                $assignment->links()->create([
                    'title' => $link['title'] ?? null,
                    'url' => $link['url'],
                ]);
            }
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('diary', 'public');

                $assignment->files()->create([
                    'path' => '/storage/' . $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                ]);
            }
        }

        foreach ($request->grades ?? [] as $userId => $grade) {
            if ($grade && in_array($grade, ['2', '3', '4', '5'])) {
                DiaryGrade::updateOrCreate(
                    [
                        'diary_assignment_id' => $assignment->id,
                        'user_id' => $userId,
                    ],
                    [
                        'grade' => $grade,
                    ]
                );
            } else {
                DiaryGrade::where('diary_assignment_id', $assignment->id)
                    ->where('user_id', $userId)
                    ->delete();
            }
        }

        return redirect('/admin/diary?class=' . urlencode($request->class) . '&week_start=' . $weekStart . '&day=' . urlencode($request->day))
            ->with('success', 'Задание и оценки сохранены');
    }

    public function destroy($id)
    {
        $assignment = DiaryAssignment::with('files')->findOrFail($id);

        foreach ($assignment->files as $file) {
            if (str_starts_with($file->path, '/storage/')) {
                $storagePath = str_replace('/storage/', '', $file->path);

                if (Storage::disk('public')->exists($storagePath)) {
                    Storage::disk('public')->delete($storagePath);
                }
            }
        }

        $assignment->delete();

        return back()->with('success', 'Задание удалено');
    }
}