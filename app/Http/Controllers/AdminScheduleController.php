<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminScheduleController extends Controller
{
    public function index(Request $request)
    {
        $weekStart = $request->filled('week_start')
            ? Carbon::parse($request->week_start)->startOfWeek(Carbon::MONDAY)
            : Carbon::now()->startOfWeek(Carbon::MONDAY);

        $weekStartDate = $weekStart->format('Y-m-d');

        $class = $request->get('class', '5');
        $selectedDay = $request->get('day');

        $days = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница'];
        $lessons = range(1, 7);

        $classes = Schedule::select('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        $schedule = [];

        foreach ($days as $day) {
            foreach ($lessons as $lesson) {
                $schedule[$day][$lesson] = Schedule::where('class', $class)
                    ->where('day', $day)
                    ->where('lesson_number', $lesson)
                    ->whereDate('week_start_date', $weekStartDate)
                    ->value('subject') ?? '';
            }
        }

        return view('admin.schedule.index', compact(
            'schedule',
            'days',
            'lessons',
            'weekStart',
            'weekStartDate',
            'class',
            'classes',
            'selectedDay'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class' => 'required|string',
            'day' => 'required|string',
            'week_start_date' => 'required|date',
            'lessons' => 'required|array',
            'lessons.*' => 'nullable|string'
        ]);

        $weekStart = Carbon::parse($request->week_start_date)
            ->startOfWeek(Carbon::MONDAY)
            ->format('Y-m-d');

        Schedule::where('class', $request->class)
            ->where('day', $request->day)
            ->whereDate('week_start_date', $weekStart)
            ->delete();

        foreach ($request->lessons as $number => $subject) {
            if ($subject && trim($subject) !== '' && trim($subject) !== '-') {
                Schedule::create([
                    'class' => $request->class,
                    'day' => $request->day,
                    'lesson_number' => $number,
                    'subject' => trim($subject),
                    'week_start_date' => $weekStart,
                ]);
            }
        }

        return redirect('/admin/schedule?class=' . urlencode($request->class) . '&week_start=' . $weekStart)
            ->with('success', 'Расписание успешно сохранено');
    }

    public function destroyDay(Request $request)
    {
        $request->validate([
            'class' => 'required|string',
            'day' => 'required|string',
            'week_start' => 'required|date'
        ]);

        $weekStart = Carbon::parse($request->week_start)
            ->startOfWeek(Carbon::MONDAY)
            ->format('Y-m-d');

        Schedule::where('class', $request->class)
            ->where('day', $request->day)
            ->whereDate('week_start_date', $weekStart)
            ->delete();

        return redirect('/admin/schedule?class=' . urlencode($request->class) . '&week_start=' . $weekStart)
            ->with('success', 'Расписание на день удалено');
    }
}