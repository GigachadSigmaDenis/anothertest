<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ScheduleTemplate;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminScheduleController extends Controller
{
    public function index(Request $request)
    {
        $weekStart = $this->weekStartFromRequest($request);
        $weekStartDate = $weekStart->toDateString();

        $selectedClass = trim((string) $request->get('class', Schedule::query()->orderBy('class')->value('class') ?: '1'));
        $selectedDay = trim((string) $request->get('day', 'Понедельник'));

        $days = $this->days();
        $lessons = range(1, 8);

        if (!in_array($selectedDay, $days, true)) {
            $selectedDay = 'Понедельник';
        }

        $classes = $this->classes();
        $schedule = $this->buildScheduleMatrix($weekStartDate, $selectedClass, $days, $lessons);
        $subjects = $this->subjectSuggestions();

        // Проверяем наличие шаблона для этого класса и дня
        $hasTemplate = ScheduleTemplate::hasTemplate($selectedClass, $selectedDay);

        $class = $selectedClass;

        return view('admin.schedule.index', compact(
            'weekStart',
            'weekStartDate',
            'selectedClass',
            'selectedDay',
            'class',
            'days',
            'lessons',
            'classes',
            'schedule',
            'subjects',
            'hasTemplate'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class' => 'required|string|max:50',
            'day' => 'required|string|max:50',
            'week_start_date' => 'required|date',
            'lessons' => 'required|array',
            'lessons.*' => 'nullable|string|max:255',
        ]);

        $weekStart = Carbon::parse($data['week_start_date'])->startOfWeek(Carbon::MONDAY)->toDateString();

        Schedule::where('class', $data['class'])
            ->where('day', $data['day'])
            ->whereDate('week_start_date', $weekStart)
            ->delete();

        foreach ($data['lessons'] as $lessonNumber => $subject) {
            $subject = trim((string) $subject);

            if ($subject === '') {
                continue;
            }

            Schedule::create([
                'class' => trim((string) $data['class']),
                'day' => trim((string) $data['day']),
                'lesson_number' => (int) $lessonNumber,
                'subject' => $subject,
                'teacher_name' => null,
                'week_start_date' => $weekStart,
            ]);
        }

        return redirect('/admin/schedule?class=' . urlencode($data['class']) . '&day=' . urlencode($data['day']) . '&week_start_date=' . $weekStart)
            ->with('success', 'Расписание сохранено.');
    }

    public function destroyDay(Request $request)
    {
        $data = $request->validate([
            'class' => 'required|string|max:50',
            'day' => 'required|string|max:50',
            'week_start_date' => 'required|date',
        ]);

        $weekStart = Carbon::parse($data['week_start_date'])->startOfWeek(Carbon::MONDAY)->toDateString();

        Schedule::where('class', $data['class'])
            ->where('day', $data['day'])
            ->whereDate('week_start_date', $weekStart)
            ->delete();

        return back()->with('success', 'Расписание за день удалено.');
    }

    // Применить шаблон к текущему дню
    public function applyTemplate(Request $request)
    {
        $data = $request->validate([
            'class' => 'required|string|max:50',
            'day' => 'required|string|max:50',
            'week_start_date' => 'required|date',
        ]);

        $weekStart = Carbon::parse($data['week_start_date'])->startOfWeek(Carbon::MONDAY)->toDateString();

        // Получаем шаблон
        $templates = ScheduleTemplate::where('class', $data['class'])
            ->where('day', $data['day'])
            ->orderBy('lesson_number')
            ->get();

        if ($templates->isEmpty()) {
            return redirect('/admin/schedule?class=' . urlencode($data['class']) . '&day=' . urlencode($data['day']) . '&week_start_date=' . $weekStart)
                ->with('error', 'Шаблон для этого класса и дня не найден.');
        }

        // Удаляем существующее расписание
        Schedule::where('class', $data['class'])
            ->where('day', $data['day'])
            ->whereDate('week_start_date', $weekStart)
            ->delete();

        // Вставляем из шаблона
        foreach ($templates as $template) {
            if ($template->subject) {
                Schedule::create([
                    'class' => $data['class'],
                    'day' => $data['day'],
                    'lesson_number' => $template->lesson_number,
                    'subject' => $template->subject,
                    'teacher_name' => $template->teacher_name,
                    'week_start_date' => $weekStart,
                ]);
            }
        }

        return redirect('/admin/schedule?class=' . urlencode($data['class']) . '&day=' . urlencode($data['day']) . '&week_start_date=' . $weekStart)
            ->with('success', 'Расписание успешно применено из шаблона.');
    }

    // Сохранить текущее расписание как шаблон
    public function saveTemplate(Request $request)
    {
        $data = $request->validate([
            'class' => 'required|string|max:50',
            'day' => 'required|string|max:50',
            'week_start_date' => 'required|date',
        ]);

        $weekStart = Carbon::parse($data['week_start_date'])->startOfWeek(Carbon::MONDAY)->toDateString();

        // Получаем текущее расписание
        $schedules = Schedule::where('class', $data['class'])
            ->where('day', $data['day'])
            ->whereDate('week_start_date', $weekStart)
            ->orderBy('lesson_number')
            ->get();

        if ($schedules->isEmpty()) {
            return redirect('/admin/schedule?class=' . urlencode($data['class']) . '&day=' . urlencode($data['day']) . '&week_start_date=' . $weekStart)
                ->with('error', 'Нет расписания для сохранения в шаблон.');
        }

        // Удаляем старый шаблон
        ScheduleTemplate::where('class', $data['class'])
            ->where('day', $data['day'])
            ->delete();

        // Сохраняем новый шаблон
        foreach ($schedules as $schedule) {
            ScheduleTemplate::create([
                'class' => $data['class'],
                'day' => $data['day'],
                'lesson_number' => $schedule->lesson_number,
                'subject' => $schedule->subject,
                'teacher_name' => $schedule->teacher_name,
            ]);
        }

        return redirect('/admin/schedule?class=' . urlencode($data['class']) . '&day=' . urlencode($data['day']) . '&week_start_date=' . $weekStart)
            ->with('success', 'Шаблон успешно сохранён.');
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

    private function subjectSuggestions(): array
    {
        return Schedule::query()
            ->whereNotNull('subject')
            ->where('subject', '!=', '')
            ->distinct()
            ->orderBy('subject')
            ->pluck('subject')
            ->map(fn ($subject) => trim((string) $subject))
            ->filter(fn ($subject) => $subject !== '')
            ->values()
            ->all();
    }

    public function checkTemplate(Request $request)
    {
        $class = $request->get('class');
        $day = $request->get('day');
        
        $hasTemplate = ScheduleTemplate::hasTemplate($class, $day);
        
        return response()->json(['hasTemplate' => $hasTemplate]);
    }
}