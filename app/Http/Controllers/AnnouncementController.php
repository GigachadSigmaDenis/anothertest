<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementRead;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('is_published', true)
            ->whereIn('audience', $this->allowedAudiences())
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->latest('published_at')
            ->latest('id')
            ->get();

        return view('announcements.index', compact('announcements'));
    }

    public function show($id)
    {
        $announcement = Announcement::where('is_published', true)
            ->whereIn('audience', $this->allowedAudiences())
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->findOrFail($id);

        return view('announcements.show', compact('announcement'));
    }

    public function markRead($id)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false], 403);
        }

        $announcement = Announcement::whereIn('audience', $this->allowedAudiences())
            ->findOrFail($id);

        AnnouncementRead::updateOrCreate(
            [
                'announcement_id' => $announcement->id,
                'user_id' => auth()->id(),
            ],
            [
                'read_at' => now(),
            ]
        );

        return response()->json(['success' => true]);
    }

    private function allowedAudiences(): array
    {
        if (!auth()->check()) {
            return ['all'];
        }

        $role = auth()->user()->role;

        if ($role === 'student') {
            return ['all', 'students'];
        }

        if (in_array($role, ['teacher', 'zam_dir', 'admin'])) {
            return ['all', 'students', 'teachers'];
        }

        return ['all'];
    }
}