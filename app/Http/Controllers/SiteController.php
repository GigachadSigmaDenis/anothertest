<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\News;
use App\Models\Teacher;
use App\Models\Schedule;
use Carbon\Carbon;

class SiteController extends Controller
{
    public function home()
    {
        $news = News::latest()->first();
        $teachers = Teacher::limit(3)->get();

        return view('home', compact('news', 'teachers'));
    }

    public function news()
    {
        $news = News::latest()->get();
        return view('news', compact('news'));
    }

    public function teachers()
    {
        $teachers = Teacher::all();
        return view('teachers', compact('teachers'));
    }

    public function schedule(Request $request)
    {
        $class = $request->get('class', '5');

        $weekStart = $request->filled('week_start')
            ? Carbon::parse($request->week_start)->startOfWeek(Carbon::MONDAY)
            : now()->startOfWeek(Carbon::MONDAY);

        $weekStartDate = $weekStart->toDateString();

        $days = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница'];
        $lessons = range(1, 7);

        $data = [];

        foreach ($lessons as $lesson) {
            foreach ($days as $day) {
                $data[$lesson][$day] = Schedule::where('class', $class)
                    ->where('day', $day)
                    ->where('lesson_number', $lesson)
                    ->whereDate('week_start_date', $weekStartDate)
                    ->value('subject') ?? '-';
            }
        }

        return view('schedule', compact(
            'data',
            'class',
            'days',
            'lessons',
            'weekStart'
        ));
    }

    public function about()
    {
        return view('about');
    }

    public function contacts()
    {
        return view('contacts');
    }
    public function general()
    {
        return view('about.general');
    }

    public function structure()
    {
        return view('about.structure');
    }

    public function documents()
    {
        return view('about.documents');
    }

    public function management()
    {
        return view('about.management');
    }

    public function newsShow($id)
    {
        $news = News::findOrFail($id);
        return view('news_show', compact('news'));
    }
    
}