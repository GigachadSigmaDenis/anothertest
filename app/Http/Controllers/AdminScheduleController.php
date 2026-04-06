<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminScheduleController extends Controller
{
    public function index(Request $request)
    {
        $weekStart = $request->week_start
            ? Carbon::parse($request->week_start)
            : Carbon::now()->startOfWeek();

        $class = $request->class;
        $day = $request->day;

        $classes = Schedule::select('class')->distinct()->pluck('class');

        $days = ['Понедельник','Вторник','Среда','Четверг','Пятница'];

        $query = Schedule::whereDate('week_start_date', $weekStart);

        if ($class) {
            $query->where('class', $class);
        }

        if ($day) {
            $query->where('day', $day);
        }

        $schedules = $query
            ->orderBy('class')
            ->orderBy('lesson_number')
            ->get();

        return view('admin.schedule.index', compact(
            'schedules',
            'classes',
            'days',
            'class',
            'day',
            'weekStart'
        ));
    }

    public function create()
    {
        return view('admin.schedule.create');
    }

    public function store(Request $request)
    {
        Schedule::create([
            'class' => $request->class,
            'day' => $request->day,
            'lesson_number' => $request->lesson_number,
            'subject' => $request->subject,
            'week_start_date' => $request->week_start_date
        ]);

        return redirect('/admin/schedule');
    }

    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        return view('admin.schedule.edit', compact('schedule'));
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $schedule->update([
            'class' => $request->class,
            'day' => $request->day,
            'lesson_number' => $request->lesson_number,
            'subject' => $request->subject,
            'week_start_date' => $request->week_start_date
        ]);

        return redirect('/admin/schedule');
    }

    public function destroy($id)
    {
        Schedule::findOrFail($id)->delete();
        return back();
    }
}