<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use App\Models\DiaryAssignment;
use App\Models\DiaryGrade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function diary(Request $request)
    {
        return $this->diaryPage($request);
    }

    public function storeDiary(Request $request)
    {
        return $this->saveDiary($request);
    }

    public function deleteDiary($id)
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

    public function grades(Request $request)
    {
        $query = DiaryGrade::with(['user', 'assignment'])
            ->whereHas('assignment');

        if ($request->filled('class')) {
            $query->whereHas('assignment', function ($q) use ($request) {
                $q->where('class', $request->class);
            });
        }

        if ($request->filled('week_start')) {
            $weekStart = Carbon::parse($request->week_start)
                ->startOfWeek(Carbon::MONDAY)
                ->format('Y-m-d');

            $query->whereHas('assignment', function ($q) use ($weekStart) {
                $q->whereDate('week_start_date', $weekStart);
            });
        }

        if ($request->filled('day')) {
            $query->whereHas('assignment', function ($q) use ($request) {
                $q->where('day', $request->day);
            });
        }

        if ($request->filled('subject')) {
            $query->whereHas('assignment', function ($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->subject . '%');
            });
        }

        $grades = $query->latest()->get();

        $days = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница'];
        $baseUrl = '/teacher/grades';
        $pageLabel = 'Учитель';

        return view('staff.grades.index', compact(
            'grades',
            'days',
            'baseUrl',
            'pageLabel'
        ));
    }

    private function diaryPage(Request $request)
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

        $baseUrl = '/teacher/diary';
        $backUrl = '/profile';
        $pageLabel = 'Учитель';

        return view('staff.diary.index', compact(
            'days',
            'class',
            'weekStart',
            'selectedDay',
            'lessons',
            'assignments',
            'students',
            'baseUrl',
            'backUrl',
            'pageLabel'
        ));
    }

    private function saveDiary(Request $request)
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

        return redirect('/teacher/diary?' . http_build_query([
            'class' => $request->class,
            'week_start' => $weekStart,
            'day' => $request->day,
        ]))->with('success', 'Задание и оценки сохранены');
    }
}