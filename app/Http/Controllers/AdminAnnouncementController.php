<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminAnnouncementController extends Controller
{
    public function index(Request $request)
    {
        // Если нажата кнопка "Сбросить"
        if ($request->has('reset')) {
            return redirect('/admin/announcements');
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

        $searchQuery = $request->get('search', '');

        return view('admin.announcements.index', compact(
            'announcements',
            'editAnnouncement',
            'searchQuery'
        ));
    }

    public function store(Request $request)
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

        return redirect('/admin/announcements')->with('success', 'Объявление успешно добавлено');
    }

    public function update(Request $request, $id)
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

        return redirect('/admin/announcements')->with('success', 'Объявление успешно обновлено');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);

        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }

        $announcement->delete();

        return redirect('/admin/announcements')->with('success', 'Объявление удалено');
    }
}