<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class AdminDocumentController extends Controller
{
    public function index()
    {
        $documents = Document::orderBy('sort_order')->orderBy('id', 'desc')->get();
        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'required|url|max:500',
        ]);

        Document::create([
            'title' => $request->title,
            'link' => $request->link,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect('/admin/documents')->with('success', 'Документ успешно добавлен');
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);
        return view('admin.documents.edit', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'required|url|max:500',
        ]);

        $document = Document::findOrFail($id);
        $document->title = $request->title;
        $document->link = $request->link;
        $document->is_published = $request->has('is_published');
        $document->save();

        return redirect('/admin/documents')->with('success', 'Документ успешно обновлен');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        $document->delete();

        return redirect('/admin/documents')->with('success', 'Документ успешно удален');
    }

    public function updateOrder(Request $request)
    {
        $order = $request->order;
        
        foreach ($order as $index => $id) {
            Document::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}