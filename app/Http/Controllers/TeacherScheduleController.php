<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeacherScheduleController extends Controller
{
    public function index(Request $request)
    {
        $weekStart = $request->week_start
            ? Carbon::parse($request->week_start)
            : Carbon::now()->startOfWeek();

        $class = $request->class;

        $days = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница'];

        $query = Schedule::query();

        if ($class) {
            $query->where('class', $class);
        }

        if ($request->week_start) {
            $query->whereDate('week_start_date', $weekStart->format('Y-m-d'));
        }

        if ($request->date_from) {
            $query->whereDate('week_start_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('week_start_date', '<=', $request->date_to);
        }

        if ($request->day) {
            $query->where('day', $request->day);
        }

        $data = $query->get();

        $schedule = [];

        foreach ($data as $item) {
            $schedule[$item->day][$item->lesson_number] = $item->subject;
        }

        return view('teacher.schedule.index', compact(
            'schedule',
            'days',
            'weekStart',
            'class'
        ));
    }

    public function create()
    {
        return view('teacher.schedule.create');
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

        $weekStart = Carbon::parse($request->week_start_date)->format('Y-m-d');

        // Удаляем старые записи за этот день, если они есть
        Schedule::where('class', $request->class)
            ->where('day', $request->day)
            ->whereDate('week_start_date', $weekStart)
            ->delete();

        // Создаем новые
        foreach ($request->lessons as $number => $subject) {
            if ($subject && trim($subject) !== '') { // Сохраняем только если есть предмет
                Schedule::create([
                    'class' => $request->class,
                    'day' => $request->day,
                    'lesson_number' => $number,
                    'subject' => trim($subject),
                    'week_start_date' => $weekStart,
                ]);
            }
        }

        return redirect('/teacher/schedule?' . http_build_query([
            'class' => $request->class,
            'week_start' => $weekStart
        ]))->with('success', 'Расписание успешно добавлено');
    }

    public function edit(Request $request)
    {
        $request->validate([
            'day' => 'required',
            'week_start' => 'required|date',
            'class' => 'nullable|string'
        ]);

        $weekStart = Carbon::parse($request->week_start)->format('Y-m-d');
        $class = $request->class;

        // Если класс не передан, пытаемся получить из БД
        if (!$class) {
            $firstLesson = Schedule::where('day', $request->day)
                ->whereDate('week_start_date', $weekStart)
                ->first();
            
            if (!$firstLesson) {
                abort(404, 'Расписание не найдено');
            }
            $class = $firstLesson->class;
        }

        $lessons = Schedule::where('class', $class)
            ->where('day', $request->day)
            ->whereDate('week_start_date', $weekStart)
            ->get();

        $data = [];
        foreach ($lessons as $l) {
            $data[$l->lesson_number] = $l->subject;
        }

        // Заполняем пустые уроки (1-7)
        for ($i = 1; $i <= 7; $i++) {
            if (!isset($data[$i])) {
                $data[$i] = '';
            }
        }

        return view('teacher.schedule.edit', [
            'data' => $data,
            'class' => $class,
            'day' => $request->day,
            'weekStart' => $weekStart
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'class' => 'required|string',
            'day' => 'required|string',
            'week_start_date' => 'required|date',
            'lessons' => 'required|array',
            'lessons.*' => 'nullable|string'
        ]);

        $weekStart = Carbon::parse($request->week_start_date)->format('Y-m-d');

        // Сначала удаляем все существующие записи за этот день
        Schedule::where('class', $request->class)
            ->where('day', $request->day)
            ->whereDate('week_start_date', $weekStart)
            ->delete();

        // Создаем новые записи только для заполненных предметов
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

        return redirect('/teacher/schedule?' . http_build_query([
            'class' => $request->class,
            'week_start' => $weekStart
        ]))->with('success', 'Расписание успешно обновлено');
    }
}