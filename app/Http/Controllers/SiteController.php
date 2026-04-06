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
        return view('home', compact('news'));
    }

    public function news()
    {
        $news = News::latest()->get();
        return view('news', compact('news'));
    }

    public function teacfhers()
    {
        $teachers = Teacher::all();
        return view('teachers', compact('teachers'));
    }

    public function schedule(Request $request)
    {
        $class = $request->class ?? '5';

        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        $days = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница'];
        $lessons = range(1, 7);

        $classes = Schedule::where('week_start_date', $weekStart)
            ->select('class')
            ->distinct()
            ->pluck('class');

        if (!$classes->contains($class)) {
            $class = $classes->first();
        }

        $data = [];

        foreach ($lessons as $lesson) {
            foreach ($days as $day) {
                $data[$lesson][$day] = Schedule::where('class', $class)
                    ->where('day', $day)
                    ->where('lesson_number', $lesson)
                    ->where('week_start_date', $weekStart)
                    ->value('subject');
            }
        }

        return view('schedule', compact('data', 'class', 'days', 'lessons', 'weekStart', 'classes'));
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