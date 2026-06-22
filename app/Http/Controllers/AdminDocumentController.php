<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminDocumentController extends Controller
{
    public function index(Request $request)
    {
        $categories = Document::categories();
        $currentCategory = trim((string) $request->get('category'));
        $search = trim((string) $request->get('q'));

        if ($currentCategory !== '' && !in_array($currentCategory, $categories, true)) {
            $currentCategory = '';
        }

        $documentsQuery = Document::query()
            ->orderByRaw($this->categoryOrderSql())
            ->orderBy('sort_order')
            ->orderByDesc('id');

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
        $editDocument = null;

        if ($request->filled('edit')) {
            $editDocument = Document::find($request->edit);
        }

        return view('admin.documents.index', compact(
            'documents',
            'editDocument',
            'categories',
            'currentCategory',
            'search'
        ));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if (!$request->filled('link') && !$request->hasFile('file')) {
            return back()
                ->withErrors(['link' => 'Укажите ссылку или загрузите файл документа.'])
                ->withInput();
        }

        $link = $data['link'] ?? null;

        if ($request->hasFile('file')) {
            $link = $request->file('file')->store('documents', 'public');
        }

        Document::create([
            'title' => trim($data['title']),
            'category' => $data['category'],
            'link' => $link,
            'sort_order' => Document::max('sort_order') + 1,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect('/admin/documents')->with('success', 'Документ добавлен.');
    }

    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $data = $this->validatedData($request, false);

        if (!$request->filled('link') && !$request->hasFile('file') && !$document->link) {
            return back()
                ->withErrors(['link' => 'Укажите ссылку или загрузите файл документа.'])
                ->withInput();
        }

        $link = $data['link'] ?? $document->link;

        if ($request->hasFile('file')) {
            $this->deleteLocalDocumentFile($document->link);
            $link = $request->file('file')->store('documents', 'public');
        }

        $document->update([
            'title' => trim($data['title']),
            'category' => $data['category'],
            'link' => $link,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect('/admin/documents')->with('success', 'Документ обновлен.');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        $this->deleteLocalDocumentFile($document->link);
        $document->delete();

        return back()->with('success', 'Документ удален.');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:documents,id',
        ]);

        foreach ($request->order as $index => $documentId) {
            Document::where('id', $documentId)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['status' => 'ok']);
    }

    private function validatedData(Request $request, bool $fileRequired = true): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'category' => ['required', 'string', Rule::in(Document::categories())],
            'link' => 'nullable|string|max:2048',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,txt',
            'is_published' => 'nullable|boolean',
        ]);
    }

    private function categoryOrderSql(): string
    {
        $cases = collect(Document::categories())
            ->values()
            ->map(fn ($category, $index) => "WHEN '" . str_replace("'", "''", $category) . "' THEN " . ($index + 1))
            ->implode(' ');

        return "CASE category {$cases} ELSE 999 END";
    }

    private function deleteLocalDocumentFile(?string $link): void
    {
        if (!$link) {
            return;
        }

        if (str_starts_with($link, 'http://') || str_starts_with($link, 'https://')) {
            return;
        }

        if (Storage::disk('public')->exists($link)) {
            Storage::disk('public')->delete($link);
        }
    }

    public function checkDuplicate(Request $request)
    {
        $field = $request->input('field');
        $value = $request->input('value');
        $excludeId = $request->input('exclude_id');

        $query = Document::where($field, $value);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return response()->json(['exists' => $query->exists()]);
    }
}
