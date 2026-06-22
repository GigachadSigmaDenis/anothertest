<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\News;
use App\Models\Schedule;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        // Получаем последнюю новость по дате публикации
        $news = News::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->first();
            
        $teachers = Teacher::latest()->take(4)->get();

        return view('home', compact('news', 'teachers'));
    }

    public function news()
    {
        $news = News::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->get();

        return view('news', compact('news'));
    }

    public function newsShow($id)
    {
        $news = News::findOrFail($id);

        return view('news_show', compact('news'));
    }

    public function teachers()
    {
        $teachers = Teacher::latest()->get();

        return view('teachers', compact('teachers'));
    }

    public function schedule(Request $request)
    {
        $weekStart = $this->weekStartFromRequest($request);
        $weekStartDate = $weekStart->toDateString();

        $selectedClass = trim((string) $request->get('class', Schedule::query()->orderBy('class')->value('class') ?: ''));
        $selectedDay = trim((string) $request->get('day', ''));

        $days = $this->days();
        $lessons = range(1, 8);

        if ($selectedDay !== '' && !in_array($selectedDay, $days, true)) {
            $selectedDay = '';
        }

        $classes = $this->classes();
        $schedule = $this->buildScheduleMatrix($weekStartDate, $selectedClass, $days, $lessons);

        // Совместимость со старым шаблоном schedule.blade.php.
        $class = $selectedClass;
        $data = [];

        foreach ($lessons as $lesson) {
            foreach ($days as $day) {
                $data[$lesson][$day] = $schedule[$day][$lesson]['subject'] ?: '-';
            }
        }

        return view('schedule', compact(
            'weekStart',
            'weekStartDate',
            'selectedClass',
            'selectedDay',
            'class',
            'days',
            'lessons',
            'classes',
            'schedule',
            'data'
        ));
    }

    public function contacts()
    {
        return view('contacts');
    }

    public function about()
    {
        return view('about');
    }

    public function general()
    {
        return view('about.general');
    }

    public function structure()
    {
        return view('about.structure');
    }

    public function documents(Request $request)
    {
        $categories = Document::categories();
        $currentCategory = trim((string) $request->get('category'));
        $search = trim((string) $request->get('q'));

        if ($currentCategory !== '' && !in_array($currentCategory, $categories, true)) {
            $currentCategory = '';
        }

        $documentsQuery = Document::query()
            ->where('is_published', true)
            ->orderByRaw($this->documentCategoryOrderSql())
            ->orderBy('sort_order')
            ->orderBy('title');

        if ($currentCategory !== '') {
            $documentsQuery->where('category', $currentCategory);
        }

        if ($search !== '') {
            $documentsQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        $documents = $documentsQuery->get();
        $documentsByCategory = $documents->groupBy(fn (Document $document) => $document->category_label);

        return view('about.documents', compact(
            'documents',
            'documentsByCategory',
            'categories',
            'currentCategory',
            'search'
        ));
    }

    public function management()
    {
        return view('about.management');
    }

    private function weekStartFromRequest(Request $request): Carbon
    {
        $date = $request->get('week_start_date', $request->get('week_start'));

        return $date
            ? Carbon::parse($date)->startOfWeek(Carbon::MONDAY)
            : now()->startOfWeek(Carbon::MONDAY);
    }

    private function days(): array
    {
        return [
            'Понедельник',
            'Вторник',
            'Среда',
            'Четверг',
            'Пятница',
        ];
    }

    private function classes()
    {
        $classes = Schedule::query()
            ->select('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class')
            ->filter()
            ->values();

        if ($classes->isEmpty()) {
            return collect(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11']);
        }

        return $classes;
    }

    private function buildScheduleMatrix(string $weekStartDate, string $selectedClass, array $days, array $lessons): array
    {
        $matrix = [];

        foreach ($days as $day) {
            foreach ($lessons as $lesson) {
                $matrix[$day][$lesson] = [
                    'subject' => '',
                ];
            }
        }

        if ($selectedClass === '') {
            return $matrix;
        }

        $records = Schedule::query()
            ->whereDate('week_start_date', $weekStartDate)
            ->where('class', $selectedClass)
            ->get();

        foreach ($records as $record) {
            $matrix[$record->day][$record->lesson_number] = [
                'subject' => (string) $record->subject,
            ];
        }

        return $matrix;
    }

    private function documentCategoryOrderSql(): string
    {
        $cases = collect(Document::categories())
            ->values()
            ->map(fn ($category, $index) => "WHEN '" . str_replace("'", "''", $category) . "' THEN " . ($index + 1))
            ->implode(' ');

        return "CASE category {$cases} ELSE 999 END";
    }
}