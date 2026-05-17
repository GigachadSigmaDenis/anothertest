@extends('layout')

@section('content')

<section class="admin-news-page">

    <div class="admin-news-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Админ-панель</span>
                <h3>Управление новостями</h3>
            </div>

            <a href="/admin/dashboard" class="section-link">
                ← Назад
            </a>
        </div>

        <p class="admin-news-hero-text">
            Здесь можно создать новость, изменить уже опубликованную запись
            или удалить её с сайта.
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

    <div class="admin-news-editor mb-4" id="editor">
        <div class="admin-news-editor-head">
            <div>
                <span class="page-label">
                    {{ $editNews ? 'Редактирование' : 'Создание' }}
                </span>

                <h4>
                    {{ $editNews ? 'Редактировать новость' : 'Добавить новость' }}
                </h4>
            </div>

            @if($editNews)
                <a href="/admin/news" class="btn btn-secondary btn-sm">
                    + Новая новость
                </a>
            @endif
        </div>

        <form method="POST"
              action="{{ $editNews ? '/admin/news/update/' . $editNews->id : '/admin/news/store' }}"
              enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="mb-3">
                        <label class="form-label">Заголовок</label>
                        <input type="text"
                               name="title"
                               class="form-control"
                               placeholder="Введите заголовок"
                               value="{{ old('title', $editNews->title ?? '') }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Текст новости</label>
                        <textarea name="content"
                                  class="form-control"
                                  rows="12"
                                  placeholder="Введите текст новости..."
                                  required>{{ old('content', $editNews->content ?? '') }}</textarea>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="admin-news-side">
                        <div class="mb-3">
                            <label class="form-label">Категория</label>

                            @php
                                $currentCategory = old('category', $editNews->category ?? 'образование');
                            @endphp

                            <select name="category" class="form-select" required>
                                <option value="безопасность" {{ $currentCategory == 'безопасность' ? 'selected' : '' }}>
                                    Безопасность
                                </option>
                                <option value="профориентация" {{ $currentCategory == 'профориентация' ? 'selected' : '' }}>
                                    Профориентация
                                </option>
                                <option value="образование" {{ $currentCategory == 'образование' ? 'selected' : '' }}>
                                    Образование
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Дата и время публикации</label>
                            <input type="datetime-local"
                                   name="published_at"
                                   class="form-control"
                                   value="{{ old('published_at', $editNews && $editNews->published_at ? \Carbon\Carbon::parse($editNews->published_at)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                        </div>

                        @if($editNews && $editNews->image)
                            <div class="mb-3">
                                <label class="form-label">Текущее изображение</label>
                                <img src="{{ asset('storage/' . $editNews->image) }}"
                                     class="admin-news-current-image"
                                     alt="{{ $editNews->title }}">
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">
                                {{ $editNews ? 'Новое изображение' : 'Изображение' }}
                            </label>

                            <input type="file"
                                   name="image"
                                   id="imageInput"
                                   class="form-control"
                                   accept="image/*">

                            <small class="text-muted">
                                Рекомендуемый размер: 800×600px
                            </small>
                        </div>

                        <div id="previewBox" class="admin-news-preview-box" style="display: none;">
                            <img id="preview" class="admin-news-preview" alt="Предпросмотр">

                            <button type="button" id="cancelBtn" class="btn btn-secondary btn-sm mt-2">
                                Отменить изображение
                            </button>
                        </div>

                        <div class="admin-news-actions">
                            <button type="submit" class="btn btn-primary">
                                {{ $editNews ? 'Обновить новость' : 'Сохранить новость' }}
                            </button>

                            @if($editNews)
                                <a href="/admin/news" class="btn btn-secondary">
                                    Отмена
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="admin-news-list">
        <div class="section-head">
            <div>
                <span class="page-label">Список</span>
                <h3>Опубликованные новости</h3>
            </div>
        </div>

        @if($news->count() > 0)
            <div class="admin-news-grid">
                @foreach($news as $item)
                    <article class="admin-news-card">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}"
                                 class="admin-news-card-image"
                                 alt="{{ $item->title }}">
                        @else
                            <div class="admin-news-card-placeholder">
                                Нет изображения
                            </div>
                        @endif

                        <div class="admin-news-card-body">
                            <span class="news-category
                                @if($item->category == 'безопасность') category-safety
                                @elseif($item->category == 'профориентация') category-career
                                @else category-education
                                @endif">
                                {{ $item->category }}
                            </span>

                            <h4>
                                {{ \Illuminate\Support\Str::limit($item->title, 70) }}
                            </h4>

                            <p class="admin-news-date">
                                {{ \Carbon\Carbon::parse($item->published_at)->format('d.m.Y H:i') }}
                            </p>

                            <p class="admin-news-text">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->content), 110) }}
                            </p>

                            <div class="admin-news-card-actions">
                                <a href="/admin/news?edit={{ $item->id }}#editor"
                                   class="btn btn-secondary btn-sm">
                                    Редактировать
                                </a>

                                <form method="POST"
                                    action="/admin/news/delete/{{ $item->id }}"
                                    onsubmit="return confirm('Удалить новость?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            class="btn btn-danger btn-sm"
                                            onclick="openDeleteNewsModal(
                                                '{{ $item->id }}',
                                                '{{ e($item->title) }}'
                                            )">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="admin-news-empty">
                <div class="admin-news-empty-icon">📰</div>
                <h4>Новостей пока нет</h4>
                <p>Добавьте первую новость через форму выше.</p>
            </div>
        @endif
    </div>

</section>

<div id="deleteNewsModal" class="admin-delete-modal" style="display: none;">
    <div class="admin-delete-modal-box">
        <button type="button" class="admin-delete-modal-close" onclick="closeDeleteNewsModal()">
            &times;
        </button>

        <div class="admin-delete-modal-icon">
            🗑
        </div>

        <h3>Удалить новость?</h3>

        <p>
            Вы действительно хотите удалить новость:
            <br>
            <strong id="deleteNewsTitle"></strong>
        </p>

        <div class="admin-delete-modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteNewsModal()">
                Отмена
            </button>

            <form method="POST" id="deleteNewsForm">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">
                    Удалить
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('imageInput');
        const preview = document.getElementById('preview');
        const previewBox = document.getElementById('previewBox');
        const cancelBtn = document.getElementById('cancelBtn');

        if (input) {
            input.addEventListener('change', function () {
                const file = this.files[0];

                if (file) {
                    preview.src = URL.createObjectURL(file);
                    previewBox.style.display = 'block';
                }
            });
        }

        cancelBtn?.addEventListener('click', function () {
            input.value = '';
            preview.src = '';
            previewBox.style.display = 'none';
        });

        const deleteModal = document.getElementById('deleteNewsModal');

        deleteModal?.addEventListener('click', function (event) {
            if (event.target === deleteModal) {
                closeDeleteNewsModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && deleteModal && deleteModal.style.display === 'flex') {
                closeDeleteNewsModal();
            }
        });
    });

    function openDeleteNewsModal(id, title) {
        const modal = document.getElementById('deleteNewsModal');
        const form = document.getElementById('deleteNewsForm');
        const titleElement = document.getElementById('deleteNewsTitle');

        form.action = '/admin/news/delete/' + id;
        titleElement.textContent = title;

        modal.style.display = 'flex';
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteNewsModal() {
        const modal = document.getElementById('deleteNewsModal');

        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
    }
</script>

@endsection