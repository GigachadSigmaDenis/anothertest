@extends('layout')

@section('content')

<section class="admin-documents-page">

    <div class="admin-documents-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Админ-панель</span>
                <h3>Управление документами</h3>
            </div>

            <a href="/admin/dashboard" class="section-link">
                ← Назад
            </a>
        </div>

        <p class="admin-documents-hero-text">
            Здесь можно добавить документ по ссылке или загрузить файл. 
            Загруженные файлы будут открываться в новой вкладке браузера.
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
                <span class="page-label">
                    {{ $editDocument ? 'Редактирование' : 'Создание' }}
                </span>

                <h4>
                    {{ $editDocument ? 'Редактировать документ' : 'Добавить документ' }}
                </h4>
            </div>

            @if($editDocument)
                <a href="/admin/documents" class="btn btn-secondary btn-sm">
                    + Новый документ
                </a>
            @endif
        </div>

        <form method="POST"
              action="{{ $editDocument ? '/admin/documents/update/' . $editDocument->id : '/admin/documents/store' }}"
              enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="mb-3">
                        <label class="form-label">Название документа</label>
                        <input type="text"
                               name="title"
                               class="form-control"
                               placeholder="Например: Устав школы"
                               value="{{ old('title', $editDocument->title ?? '') }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ссылка на документ</label>
                        <input type="url"
                               name="link"
                               class="form-control"
                               placeholder="https://example.com/document.pdf"
                               value="{{ old('link', $editDocument && !str_starts_with($editDocument->link, '/storage/') ? $editDocument->link : '') }}">

                        <small class="text-muted">
                            Можно указать внешнюю ссылку или загрузить файл ниже.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Файл документа</label>
                        <input type="file"
                               name="file"
                               id="documentFileInput"
                               class="form-control">

                        <small class="text-muted">
                            Лучше использовать PDF — он обычно открывается прямо в браузере.
                        </small>
                    </div>

                    <div id="documentFilePreview" class="admin-document-file-preview" style="display: none;">
                        <span>Выбран файл:</span>
                        <strong id="documentFileName"></strong>

                        <button type="button" id="documentFileCancel" class="btn btn-secondary btn-sm">
                            Отменить файл
                        </button>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="admin-documents-side">
                        @if($editDocument && $editDocument->link)
                            <div class="mb-3">
                                <label class="form-label">Текущий документ</label>

                                <a href="{{ $editDocument->link }}"
                                   target="_blank"
                                   class="admin-current-document-link">
                                    Открыть текущий документ →
                                </a>

                                <small class="text-muted d-block mt-2">
                                    Если загрузить новый файл или указать новую ссылку, текущий документ будет заменён.
                                </small>
                            </div>
                        @endif

                        <div class="form-check admin-document-check">
                            <input type="checkbox"
                                   name="is_published"
                                   class="form-check-input"
                                   id="is_published"
                                   {{ old('is_published', $editDocument->is_published ?? true) ? 'checked' : '' }}>

                            <label class="form-check-label" for="is_published">
                                Опубликовать документ
                            </label>
                        </div>

                        <div class="admin-documents-actions mt-4">
                            <button type="submit" class="btn btn-primary">
                                {{ $editDocument ? 'Обновить документ' : 'Сохранить документ' }}
                            </button>

                            @if($editDocument)
                                <a href="/admin/documents" class="btn btn-secondary">
                                    Отмена
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="admin-documents-list">
        <div class="section-head">
            <div>
                <span class="page-label">Список</span>
                <h3>Документы сайта</h3>
            </div>
        </div>

        @if($documents->count() > 0)
            <div class="admin-documents-grid" id="documentsSortable">
                @foreach($documents as $document)
                    <article class="admin-document-card" data-id="{{ $document->id }}">
                        <div class="admin-document-drag">
                            ⋮⋮
                        </div>

                        <div class="admin-document-icon">
                            PDF
                        </div>

                        <div class="admin-document-card-body">
                            <h4>{{ \Illuminate\Support\Str::limit($document->title, 70) }}</h4>

                            <a href="{{ $document->link }}"
                               target="_blank"
                               class="admin-document-link">
                                Открыть документ →
                            </a>

                            <div class="mt-3">
                                @if($document->is_published)
                                    <span class="admin-document-status status-published">
                                        Опубликовано
                                    </span>
                                @else
                                    <span class="admin-document-status status-hidden">
                                        Скрыто
                                    </span>
                                @endif
                            </div>

                            <div class="admin-document-card-actions">
                                <a href="/admin/documents?edit={{ $document->id }}#editor"
                                   class="btn btn-secondary btn-sm">
                                    Редактировать
                                </a>

                                <button type="button"
                                        class="btn btn-danger btn-sm"
                                        onclick="openDeleteDocumentModal(
                                            '{{ $document->id }}',
                                            '{{ e($document->title) }}'
                                        )">
                                    Удалить
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="admin-documents-empty">
                <div class="admin-documents-empty-icon">📄</div>
                <h4>Документы пока не добавлены</h4>
                <p>Добавьте первый документ через форму выше.</p>
            </div>
        @endif
    </div>

</section>

<div id="deleteDocumentModal" class="admin-delete-modal" style="display: none;">
    <div class="admin-delete-modal-box">
        <button type="button" class="admin-delete-modal-close" onclick="closeDeleteDocumentModal()">
            &times;
        </button>

        <div class="admin-delete-modal-icon">
            🗑
        </div>

        <h3>Удалить документ?</h3>

        <p>
            Вы действительно хотите удалить документ:
            <br>
            <strong id="deleteDocumentTitle"></strong>
        </p>

        <div class="admin-delete-modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteDocumentModal()">
                Отмена
            </button>

            <form method="POST" id="deleteDocumentForm">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">
                    Удалить
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('documentFileInput');
        const filePreview = document.getElementById('documentFilePreview');
        const fileName = document.getElementById('documentFileName');
        const fileCancel = document.getElementById('documentFileCancel');

        fileInput?.addEventListener('change', function () {
            const file = this.files[0];

            if (file) {
                fileName.textContent = file.name;
                filePreview.style.display = 'flex';
            }
        });

        fileCancel?.addEventListener('click', function () {
            fileInput.value = '';
            fileName.textContent = '';
            filePreview.style.display = 'none';
        });

        const sortableElement = document.getElementById('documentsSortable');

        if (sortableElement) {
            new Sortable(sortableElement, {
                handle: '.admin-document-drag',
                animation: 150,
                onEnd: function () {
                    const items = document.querySelectorAll('#documentsSortable .admin-document-card');
                    const order = [];

                    items.forEach(function (item) {
                        order.push(item.dataset.id);
                    });

                    fetch('/admin/documents/update-order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ order: order })
                    });
                }
            });
        }

        const deleteModal = document.getElementById('deleteDocumentModal');

        deleteModal?.addEventListener('click', function (event) {
            if (event.target === deleteModal) {
                closeDeleteDocumentModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && deleteModal && deleteModal.style.display === 'flex') {
                closeDeleteDocumentModal();
            }
        });
    });

    function openDeleteDocumentModal(id, title) {
        const modal = document.getElementById('deleteDocumentModal');
        const form = document.getElementById('deleteDocumentForm');
        const titleElement = document.getElementById('deleteDocumentTitle');

        form.action = '/admin/documents/delete/' + id;
        titleElement.textContent = title;

        modal.style.display = 'flex';
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteDocumentModal() {
        const modal = document.getElementById('deleteDocumentModal');

        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
    }
</script>

@endsection