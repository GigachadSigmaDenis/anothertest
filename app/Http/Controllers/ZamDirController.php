<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Schedule;
use App\Models\DiaryAssignment;
use App\Models\DiaryGrade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Announcement;

class ZamDirController extends Controller
{
    public function classes(Request $request)
    {
        $users = User::whereNotIn('role', ['teacher', 'admin', 'zam_dir'])
            ->orderBy('full_name')
            ->get();

        return view('zam.classes', compact('users'));
    }

    public function updateClass(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'class' => 'required|string'
        ]);

        $user = User::findOrFail($request->user_id);

        if (in_array($user->role, ['teacher', 'admin', 'zam_dir'])) {
            return back()->with('error', 'Нельзя изменить класс сотрудника');
        }

        if ($request->class === 'none') {
            $user->role = 'guest';
            $user->studend_class = 'none';
        } else {
            $user->role = 'student';
            $user->studend_class = $request->class;
        }

        $user->save();

        return back()->with('success', 'Класс успешно обновлён');
    }

    public function diary(Request $request)
    {
        return $this->diaryPage($request, 'zam');
    }

    public function storeDiary(Request $request)
    {
        return $this->saveDiary($request, '/zam/diary');
    }

    public function deleteDiary($id)
    {
        return $this->deleteDiaryAssignment($id);
    }

    public function grades(Request $request)
    {
        return $this->gradesPage($request, 'zam');
    }

    private function diaryPage(Request $request, string $area)
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

        $baseUrl = $area === 'zam' ? '/zam/diary' : '/teacher/diary';
        $backUrl = '/profile';
        $pageLabel = $area === 'zam' ? 'Заместитель директора' : 'Учитель';

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

    private function saveDiary(Request $request, string $redirectBase)
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

        return redirect($redirectBase . '?' . http_build_query([
            'class' => $request->class,
            'week_start' => $weekStart,
            'day' => $request->day,
        ]))->with('success', 'Задание и оценки сохранены');
    }

    private function deleteDiaryAssignment($id)
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

    private function gradesPage(Request $request, string $area)
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
        $baseUrl = $area === 'zam' ? '/zam/grades' : '/teacher/grades';
        $pageLabel = $area === 'zam' ? 'Заместитель директора' : 'Учитель';

        return view('staff.grades.index', compact(
            'grades',
            'days',
            'baseUrl',
            'pageLabel'
        ));
    }

    public function announcements(Request $request)
    {
        // Если нажата кнопка "Сбросить"
        if ($request->has('reset')) {
            return redirect('/zam/announcements');
        }
        
        $query = Announcement::query();
        
        // Поиск по заголовку
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('title', 'like', '%' . $search . '%');
        }
        
        $announcements = $query->latest('published_at')
            ->latest('id')
            ->get();

        $editAnnouncement = null;

        if ($request->filled('edit')) {
            $editAnnouncement = Announcement::find($request->edit);
        }

        $baseUrl = '/zam/announcements';
        $backUrl = '/profile';
        $pageLabel = 'Заместитель директора';
        $searchQuery = $request->get('search', '');

        return view('staff.announcements.index', compact(
            'announcements',
            'editAnnouncement',
            'baseUrl',
            'backUrl',
            'pageLabel',
            'searchQuery'
        ));
    }

    public function storeAnnouncement(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:event,info',
            'audience' => 'required|in:all,students,teachers',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'published_at' => 'nullable|date',
            'event_at' => 'nullable|date',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('announcements', 'public');
        }

        $data['is_published'] = $request->has('is_published');
        $data['created_by_id'] = auth()->id();
        $data['published_at'] = $data['published_at'] ?? now();

        Announcement::create($data);

        return redirect('/zam/announcements')->with('success', 'Объявление успешно добавлено');
    }

    public function updateAnnouncement(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $data = $request->validate([
            'type' => 'required|in:event,info',
            'audience' => 'required|in:all,students,teachers',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'published_at' => 'nullable|date',
            'event_at' => 'nullable|date',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }

            $data['image'] = $request->file('image')->store('announcements', 'public');
        }

        $data['is_published'] = $request->has('is_published');
        $data['published_at'] = $data['published_at'] ?? now();

        $announcement->update($data);

        return redirect('/zam/announcements')->with('success', 'Объявление успешно обновлено');
    }

    public function deleteAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);

        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }

        $announcement->delete();

        return redirect('/zam/announcements')->with('success', 'Объявление удалено');
    }
}