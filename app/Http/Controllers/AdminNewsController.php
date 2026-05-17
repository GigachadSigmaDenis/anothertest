<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminNewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::latest()->get();

        $editNews = null;

        if ($request->filled('edit')) {
            $editNews = News::find($request->edit);
        }

        return view('admin.news.index', compact('news', 'editNews'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
            'published_at' => 'nullable',
            'image' => 'nullable|image'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $data['published_at'] = $request->published_at ?? now();

        News::create($data);

        return redirect('/admin/news')->with('success', 'Новость успешно добавлена');
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $data = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
            'published_at' => 'nullable',
            'image' => 'nullable|image'
        ]);

        $data['published_at'] = $request->published_at ?? now();

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }

            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $news->update($data);

        return redirect('/admin/news')->with('success', 'Новость успешно обновлена');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect('/admin/news')->with('success', 'Новость удалена');
    }
}