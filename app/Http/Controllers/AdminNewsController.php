<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminNewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();
        
        // Поиск по названию
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('title', 'like', '%' . $search . '%');
        }
        
        // Сортировка по дате публикации (новые сверху)
        $news = $query->orderBy('published_at', 'desc')->get();

        $editNews = null;

        if ($request->filled('edit')) {
            $editNews = News::find($request->edit);
        }

        // Сохраняем поисковый запрос для отображения в форме
        $searchQuery = $request->get('search', '');

        return view('admin.news.index', compact('news', 'editNews', 'searchQuery'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category' => 'required',
            'published_at' => 'nullable',
            'image' => 'nullable|image'
        ]);

        // Обрезаем заголовок до 255 символов
        $data['title'] = substr($data['title'], 0, 255);

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
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category' => 'required',
            'published_at' => 'nullable',
            'image' => 'nullable|image'
        ]);

        // Обрезаем заголовок до 255 символов
        $data['title'] = substr($data['title'], 0, 255);

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