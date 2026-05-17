<?php

namespace App\Http\Controllers;

use App\Models\DiaryAssignment;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentDiaryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'student') {
            abort(403, 'Доступ только для учеников');
        }

        if (!$user->studend_class || $user->studend_class === 'none') {
            return view('diary.student', [
                'hasClass' => false,
                'days' => [],
                'selectedDay' => null,
                'weekStart' => now()->startOfWeek(Carbon::MONDAY),
                'lessons' => collect(),
                'diaryData' => [],
            ]);
        }

        $days = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница'];

        $weekStart = $request->filled('week_start')
            ? Carbon::parse($request->week_start)->startOfWeek(Carbon::MONDAY)
            : now()->startOfWeek(Carbon::MONDAY);

        $selectedDay = $request->get('day', $days[0]);

        $lessons = Schedule::where('class', $user->studend_class)
            ->whereDate('week_start_date', $weekStart->format('Y-m-d'))
            ->where('day', $selectedDay)
            ->orderBy('lesson_number')
            ->get();

        $diaryData = [];

        foreach ($lessons as $lesson) {
            $assignment = DiaryAssignment::with(['links', 'files', 'grades'])
                ->where('class', $user->studend_class)
                ->whereDate('week_start_date', $weekStart->format('Y-m-d'))
                ->where('day', $selectedDay)
                ->where('lesson_number', $lesson->lesson_number)
                ->where('subject', $lesson->subject)
                ->first();

            $grade = null;

            if ($assignment) {
                $grade = $assignment->grades()
                    ->where('user_id', $user->id)
                    ->value('grade');
            }

            $diaryData[$lesson->lesson_number] = [
                'lesson' => $lesson,
                'assignment' => $assignment,
                'grade' => $grade,
            ];
        }

        return view('diary.student', [
            'hasClass' => true,
            'days' => $days,
            'selectedDay' => $selectedDay,
            'weekStart' => $weekStart,
            'lessons' => $lessons,
            'diaryData' => $diaryData,
        ]);
    }
}