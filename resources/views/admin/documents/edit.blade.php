@extends('layout')

@section('content')

<div class="card p-4">
    <h2 class="text-center mb-4">Редактировать документ</h2>

    <form method="POST" action="/admin/documents/update/{{ $document->id }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Название документа *</label>
            <input type="text" name="title" class="form-control" value="{{ $document->title }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ссылка на документ *</label>
            <input type="url" name="link" class="form-control" value="{{ $document->link }}" required>
            <small class="text-muted">Введите полный URL к документу (PDF, DOC и т.д.)</small>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_published" class="form-check-input" id="is_published" {{ $document->is_published ? 'checked' : '' }}>
            <label class="form-check-label" for="is_published">Опубликован</label>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Обновить</button>
            <a href="/admin/documents" class="btn btn-secondary">Отмена</a>
        </div>

    </form>
</div>

@endsection