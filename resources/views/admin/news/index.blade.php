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
                            <label class="form-label">Дата публикации</label>
                            <input type="date"
                                   name="published_at"
                                   class="form-control"
                                   value="{{ old('published_at', $editNews && $editNews->published_at ? \Carbon\Carbon::parse($editNews->published_at)->format('Y-m-d') : now()->format('Y-m-d')) }}">
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

    <!-- Фильтр поиска -->
    <div class="admin-news-filter mb-4">
        <form method="GET" action="/admin/news">
            <div class="row">
                <div class="col-md-8">
                    <label class="form-label">Поиск по названию</label>
                    <div class="d-flex">
                        <input type="text"
                            name="search"
                            class="form-control"
                            placeholder="Введите ключевое слово для поиска..."
                            value="{{ $searchQuery ?? '' }}"
                            style="border-radius: 8px 0 0 8px;">
                        <button type="submit" class="btn btn-primary" style="border-radius: 0 8px 8px 0; white-space: nowrap;">
                            Найти
                        </button>
                        @if(!empty($searchQuery))
                            <a href="/admin/news" class="btn btn-secondary" style="border-radius: 8px; margin-left: 6px; white-space: nowrap;">
                                Сбросить
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    @if(!empty($searchQuery))
                        <div class="ms-3 mb-1">
                            <span class="badge bg-primary p-2 px-3" style="font-size: 15px; font-weight: 600;">
                                Найдено новостей: {{ $news->count() }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="admin-news-list">
        <div class="section-head">
            <div>
                <span class="page-label">Список</span>
                <h3>Опубликованные новости</h3>
                @if(!empty($searchQuery))
                    <small class="text-muted ms-2">(поиск: "{{ $searchQuery }}")</small>
                @endif
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
                                {{ \Carbon\Carbon::parse($item->published_at)->format('d.m.Y') }}
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
                <p>
                    @if(!empty($searchQuery))
                        По запросу "{{ $searchQuery }}" ничего не найдено.
                        Попробуйте изменить поисковый запрос.
                    @else
                        Добавьте первую новость через форму выше.
                    @endif
                </p>
                @if(!empty($searchQuery))
                    <a href="/admin/news" class="btn btn-secondary btn-sm mt-2">
                        Показать все новости
                    </a>
                @endif
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

<style>
.admin-news-filter {
    background: #ffffff;
    border: 1px solid #dbe3ef;
    border-radius: 20px;
    padding: 22px;
    box-shadow: 0 8px 24px rgba(15, 63, 134, 0.06);
}

.admin-news-filter .form-control {
    color: #162033;
}

.admin-news-filter .btn svg {
    display: inline-block;
    vertical-align: middle;
    margin-right: 4px;
}

.admin-news-current-image {
    width: 100%;
    max-height: 200px;
    object-fit: cover;
    border-radius: 12px;
    border: 1px solid #dbe3ef;
}

.admin-news-preview-box {
    margin-top: 12px;
}

.admin-news-preview {
    width: 100%;
    max-height: 200px;
    object-fit: cover;
    border-radius: 12px;
    border: 1px solid #dbe3ef;
}

.admin-news-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 16px;
}

.admin-news-actions .btn {
    flex: 1;
    min-width: 120px;
}

.admin-news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin-top: 16px;
}

.admin-news-card {
    background: #ffffff;
    border: 1px solid #dbe3ef;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(15, 63, 134, 0.06);
    transition: 0.2s ease;
}

.admin-news-card:hover {
    box-shadow: 0 8px 24px rgba(15, 63, 134, 0.12);
}

.admin-news-card-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
}

.admin-news-card-placeholder {
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fbff;
    color: #64748b;
    border-bottom: 1px solid #dbe3ef;
}

.admin-news-card-body {
    padding: 18px;
}

.admin-news-card-body h4 {
    font-size: 18px;
    font-weight: 700;
    color: #0f3f86;
    margin: 10px 0 8px;
}

.admin-news-date {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 10px;
}

.admin-news-text {
    font-size: 14px;
    color: #162033;
    line-height: 1.6;
    margin-bottom: 14px;
}

.admin-news-card-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.admin-news-empty {
    text-align: center;
    padding: 48px 20px;
    background: #f8fbff;
    border-radius: 16px;
    border: 1px dashed #dbe3ef;
}

.admin-news-empty-icon {
    font-size: 48px;
    margin-bottom: 12px;
}

.admin-news-empty h4 {
    color: #0f3f86;
    margin-bottom: 8px;
}

.admin-news-empty p {
    color: #64748b;
    margin-bottom: 0;
}

.admin-news-empty .btn {
    min-width: 200px;
}

.news-category {
    display: inline-block;
    border-radius: 999px;
    padding: 5px 14px;
    font-size: 12px;
    font-weight: 700;
}

.category-safety {
    background: #fef3c7;
    color: #92400e;
}

.category-career {
    background: #dbeafe;
    color: #1e40af;
}

.category-education {
    background: #d1fae5;
    color: #065f46;
}

@media (max-width: 768px) {
    .admin-news-grid {
        grid-template-columns: 1fr;
    }

    .admin-news-filter .d-flex {
        flex-direction: column;
    }

    .admin-news-filter .btn {
        width: 100%;
    }

    .admin-news-actions .btn {
        flex: none;
        width: 100%;
    }
}

/* Delete Modal */
.admin-delete-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 2100;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: rgba(15, 32, 51, 0.58);
}

.admin-delete-modal-box {
    position: relative;
    width: min(100%, 460px);
    max-height: calc(100vh - 40px);
    overflow-y: auto;
    background: #ffffff;
    color: #162033;
    border: 1px solid #dbe3ef;
    border-radius: 22px;
    box-shadow: 0 24px 70px rgba(15, 63, 134, 0.24);
    padding: 28px;
    text-align: center;
}

.admin-delete-modal-box h3 {
    color: #0f3f86;
    margin-bottom: 12px;
}

.admin-delete-modal-box p,
.admin-delete-modal-box strong {
    color: #162033;
    overflow-wrap: anywhere;
}

.admin-delete-modal-close {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 38px;
    height: 38px;
    border: 0;
    border-radius: 50%;
    background: #eef5ff;
    color: #0f3f86;
    font-size: 26px;
    line-height: 1;
    cursor: pointer;
}

.admin-delete-modal-icon {
    font-size: 48px;
    margin-bottom: 12px;
}

.admin-delete-modal-actions {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
}

.admin-delete-modal-actions .btn {
    min-width: 100px;
}

body.modal-open {
    overflow: hidden;
}
</style>

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