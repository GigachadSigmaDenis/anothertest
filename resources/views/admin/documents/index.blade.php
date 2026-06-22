@extends('layout')

@section('content')
<section class="admin-documents-page">
    <div class="admin-documents-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Админ-панель</span>
                <h3>Управление документами</h3>
            </div>
            <a href="/admin/dashboard" class="section-link">← Назад</a>
        </div>
        <p class="admin-documents-hero-text">
            Здесь можно добавить документ по ссылке или загрузить файл, а также выбрать одну из фиксированных категорий для группировки на странице документов.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <strong>Проверьте форму:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-documents-editor mb-4" id="editor">
        <div class="admin-documents-editor-head">
            <div>
                <span class="page-label">{{ $editDocument ? 'Редактирование' : 'Создание' }}</span>
                <h4>{{ $editDocument ? 'Редактировать документ' : 'Добавить документ' }}</h4>
            </div>
            @if($editDocument)
                <a href="/admin/documents" class="btn btn-secondary btn-sm">Отменить</a>
            @endif
        </div>

        <form method="POST"
              enctype="multipart/form-data"
              action="{{ $editDocument ? '/admin/documents/update/' . $editDocument->id : '/admin/documents/store' }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Название документа</label>
                    <input type="text"
                           name="title"
                           class="form-control"
                           value="{{ old('title', $editDocument->title ?? '') }}"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Категория</label>
                    <select name="category" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ old('category', $editDocument->category ?? 'Документы') === $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ссылка на документ</label>
                    <input type="text"
                           name="link"
                           class="form-control"
                           placeholder="https://... или локальный путь"
                           value="{{ old('link', $editDocument->link ?? '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Файл документа</label>
                    <input type="file" name="file" class="form-control">
                    @if($editDocument && $editDocument->link)
                        <small class="text-muted d-block mt-1">Текущий файл/ссылка: {{ $editDocument->link }}</small>
                    @endif
                </div>

                <div class="col-md-12">
                    <label class="form-check">
                        <input type="checkbox"
                               name="is_published"
                               value="1"
                               class="form-check-input"
                               {{ old('is_published', $editDocument->is_published ?? true) ? 'checked' : '' }}>
                        <span class="form-check-label">Показывать на сайте</span>
                    </label>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    {{ $editDocument ? 'Сохранить изменения' : 'Добавить документ' }}
                </button>
                @if($editDocument)
                    <a href="/admin/documents" class="btn btn-outline-secondary">Не редактировать</a>
                @endif
            </div>
        </form>
    </div>

    <div class="admin-documents-list">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h4 class="mb-0">Список документов</h4>
            <form method="GET" action="/admin/documents" class="d-flex flex-wrap gap-2 align-items-center">
                <input type="text"
                       name="q"
                       class="form-control form-control-sm"
                       style="width:220px;"
                       value="{{ $search ?? '' }}"
                       placeholder="Поиск по названию">
                <select name="category" class="form-select form-select-sm" style="width:220px;">
                    <option value="">Все категории</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ $currentCategory === $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Фильтр</button>
                @if($currentCategory !== '' || ($search ?? '') !== '')
                    <a href="/admin/documents" class="btn btn-outline-secondary btn-sm">Сбросить</a>
                @endif
            </form>
        </div>

        @if($documents->count())
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Категория</th>
                            <th>Файл / ссылка</th>
                            <th>Статус</th>
                            <th class="text-end">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $document)
                            <tr>
                                <td>{{ $document->title }}</td>
                                <td>{{ $document->category_label }}</td>
                                <td>
                                    @if($document->link)
                                        @php
                                            $href = str_starts_with($document->link, 'http://') || str_starts_with($document->link, 'https://')
                                                ? $document->link
                                                : asset('storage/' . $document->link);
                                        @endphp
                                        <a href="{{ $href }}" target="_blank" rel="noopener">Открыть</a>
                                    @else
                                        <span class="text-muted">Нет ссылки</span>
                                    @endif
                                </td>
                                <td>
                                    @if($document->is_published)
                                        <span class="badge bg-success">Опубликован</span>
                                    @else
                                        <span class="badge bg-secondary">Скрыт</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="/admin/documents?edit={{ $document->id }}#editor" class="btn btn-sm btn-outline-primary">Редактировать</a>
                                    <form method="POST" action="/admin/documents/delete/{{ $document->id }}" class="d-inline" onsubmit="return confirm('Удалить документ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-secondary">Документы пока не добавлены.</div>
        @endif
    </div>
</section>
@endsection
