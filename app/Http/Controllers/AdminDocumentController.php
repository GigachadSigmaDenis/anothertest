<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDocumentController extends Controller
{
    public function index(Request $request)
    {
        $documents = Document::orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->get();

        $editDocument = null;

        if ($request->filled('edit')) {
            $editDocument = Document::find($request->edit);
        }

        return view('admin.documents.index', compact('documents', 'editDocument'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'nullable|url|max:500',
            'file' => 'nullable|file|max:20480',
        ]);

        if (!$request->filled('link') && !$request->hasFile('file')) {
            return back()
                ->withInput()
                ->withErrors(['file' => 'Укажите ссылку или загрузите файл документа.']);
        }

        $link = $request->link;

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('documents', 'public');
            $link = '/storage/' . $path;
        }

        Document::create([
            'title' => $request->title,
            'link' => $link,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect('/admin/documents')->with('success', 'Документ успешно добавлен');
    }

    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'nullable|url|max:500',
            'file' => 'nullable|file|max:20480',
        ]);

        if (!$request->filled('link') && !$request->hasFile('file') && !$document->link) {
            return back()
                ->withInput()
                ->withErrors(['file' => 'Укажите ссылку или загрузите файл документа.']);
        }

        $document->title = $request->title;

        if ($request->hasFile('file')) {
            $this->deleteLocalDocumentFile($document->link);

            $path = $request->file('file')->store('documents', 'public');
            $document->link = '/storage/' . $path;
        } elseif ($request->filled('link')) {
            $this->deleteLocalDocumentFile($document->link);

            $document->link = $request->link;
        }

        $document->is_published = $request->has('is_published');
        $document->save();

        return redirect('/admin/documents')->with('success', 'Документ успешно обновлён');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        $this->deleteLocalDocumentFile($document->link);

        $document->delete();

        return redirect('/admin/documents')->with('success', 'Документ успешно удалён');
    }

    public function updateOrder(Request $request)
    {
        $order = $request->order ?? [];

        foreach ($order as $index => $id) {
            Document::where('id', $id)->update([
                'sort_order' => $index + 1
            ]);
        }

        return response()->json(['success' => true]);
    }

    private function deleteLocalDocumentFile(?string $link): void
    {
        if (!$link) {
            return;
        }

        if (str_starts_with($link, '/storage/')) {
            $path = str_replace('/storage/', '', $link);

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}