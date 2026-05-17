@extends('layout')

@section('content')

<section class="admin-announcements-page">

    <div class="admin-announcements-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">{{ $pageLabel }}</span>
                <h3>Управление объявлениями</h3>
            </div>

            <a href="{{ $backUrl }}" class="section-link">← Назад</a>
        </div>

        <p class="admin-announcements-hero-text">
            Здесь можно создавать объявления, мероприятия, выбирать аудиторию,
            добавлять описание и изображение.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-info alert-dismissible fade show mb-4">
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

    <div class="admin-announcements-editor mb-4" id="editor">
        <div class="admin-announcements-editor-head">
            <div>
                <span class="page-label">
                    {{ $editAnnouncement ? 'Редактирование' : 'Создание' }}
                </span>

                <h4>
                    {{ $editAnnouncement ? 'Редактировать объявление' : 'Добавить объявление' }}
                </h4>
            </div>

            @if($editAnnouncement)
                <a href="{{ $baseUrl }}" class="btn btn-secondary btn-sm">
                    + Новое объявление
                </a>
            @endif
        </div>

        <form method="POST"
              action="{{ $editAnnouncement ? $baseUrl . '/update/' . $editAnnouncement->id : $baseUrl . '/store' }}"
              enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="mb-3">
                        <label class="form-label">Заголовок</label>

                        <input type="text"
                               name="title"
                               class="form-control"
                               value="{{ old('title', $editAnnouncement->title ?? '') }}"
                               placeholder="Например: Родительское собрание"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Описание</label>

                        <textarea name="description"
                                  class="form-control"
                                  rows="8"
                                  placeholder="Текст объявления...">{{ old('description', $editAnnouncement->description ?? '') }}</textarea>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="admin-announcements-side">
                        @php
                            $currentType = old('type', $editAnnouncement->type ?? 'info');
                            $currentAudience = old('audience', $editAnnouncement->audience ?? 'all');
                        @endphp

                        <div class="mb-3">
                            <label class="form-label">Тип</label>

                            <select name="type" class="form-select" required>
                                <option value="info" {{ $currentType === 'info' ? 'selected' : '' }}>
                                    Просто информирование
                                </option>

                                <option value="event" {{ $currentType === 'event' ? 'selected' : '' }}>
                                    Мероприятие
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Для кого</label>

                            <select name="audience" class="form-select" required>
                                <option value="all" {{ $currentAudience === 'all' ? 'selected' : '' }}>
                                    Для всех
                                </option>

                                <option value="students" {{ $currentAudience === 'students' ? 'selected' : '' }}>
                                    Для учеников
                                </option>

                                <option value="teachers" {{ $currentAudience === 'teachers' ? 'selected' : '' }}>
                                    Для учителей
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Дата публикации</label>

                            <input type="datetime-local"
                                   name="published_at"
                                   class="form-control"
                                   value="{{ old('published_at', $editAnnouncement && $editAnnouncement->published_at ? $editAnnouncement->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Дата проведения</label>

                            <input type="datetime-local"
                                   name="event_at"
                                   class="form-control"
                                   value="{{ old('event_at', $editAnnouncement && $editAnnouncement->event_at ? $editAnnouncement->event_at->format('Y-m-d\TH:i') : '') }}">

                            <small class="text-muted">
                                Если указана дата проведения, объявление считается мероприятием по времени.
                            </small>
                        </div>

                        @if($editAnnouncement && $editAnnouncement->image)
                            <div class="mb-3">
                                <label class="form-label">Текущее изображение</label>

                                <img src="{{ asset('storage/' . $editAnnouncement->image) }}"
                                     class="admin-announcement-current-image"
                                     alt="{{ $editAnnouncement->title }}">
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Изображение</label>

                            <input type="file"
                                   name="image"
                                   id="announcementImageInput"
                                   class="form-control"
                                   accept="image/*">
                        </div>

                        <div id="announcementImagePreviewBox" class="admin-announcement-preview-box" style="display: none;">
                            <img id="announcementImagePreview"
                                 class="admin-announcement-current-image"
                                 alt="Предпросмотр">

                            <button type="button"
                                    id="announcementImageCancel"
                                    class="btn btn-secondary btn-sm mt-2">
                                Отменить изображение
                            </button>
                        </div>

                        <div class="form-check admin-announcement-check">
                            <input type="checkbox"
                                   name="is_published"
                                   id="is_published"
                                   class="form-check-input"
                                   {{ old('is_published', $editAnnouncement->is_published ?? true) ? 'checked' : '' }}>

                            <label for="is_published" class="form-check-label">
                                Опубликовать
                            </label>
                        </div>

                        <div class="admin-announcements-actions mt-4">
                            <button type="submit" class="btn btn-primary">
                                {{ $editAnnouncement ? 'Обновить' : 'Сохранить' }}
                            </button>

                            @if($editAnnouncement)
                                <a href="{{ $baseUrl }}" class="btn btn-secondary">
                                    Отмена
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="admin-announcements-list">
        <div class="section-head">
            <div>
                <span class="page-label">Список</span>
                <h3>Объявления</h3>
            </div>
        </div>

        @if($announcements->count() > 0)
            <div class="admin-announcements-grid">
                @foreach($announcements as $announcement)
                    <article class="admin-announcement-card">
                        @if($announcement->image)
                            <img src="{{ asset('storage/' . $announcement->image) }}"
                                 class="admin-announcement-card-image"
                                 alt="{{ $announcement->title }}">
                        @else
                            <div class="admin-announcement-card-placeholder">
                                Нет изображения
                            </div>
                        @endif

                        <div class="admin-announcement-card-body">
                            <div class="announcement-meta">
                                <span class="announcement-type {{ $announcement->type === 'event' ? 'type-event' : 'type-info' }}">
                                    {{ $announcement->type === 'event' ? 'Мероприятие' : 'Информация' }}
                                </span>

                                <span class="announcement-audience">
                                    @if($announcement->audience === 'students')
                                        Для учеников
                                    @elseif($announcement->audience === 'teachers')
                                        Для учителей
                                    @else
                                        Для всех
                                    @endif
                                </span>

                                @if($announcement->is_published)
                                    <span class="admin-document-status status-published">
                                        Опубликовано
                                    </span>
                                @else
                                    <span class="admin-document-status status-hidden">
                                        Скрыто
                                    </span>
                                @endif
                            </div>

                            <h4 class="announcement-title-clamp">
                                {{ $announcement->title }}
                            </h4>

                            <p class="admin-announcement-date">
                                Публикация:
                                {{ optional($announcement->published_at)->format('d.m.Y H:i') ?? '—' }}
                            </p>

                            @if($announcement->event_at)
                                <p class="admin-announcement-date">
                                    Проведение:
                                    {{ $announcement->event_at->format('d.m.Y H:i') }}
                                </p>
                            @endif

                            <p class="admin-announcement-text announcement-description-clamp">
                                {{ strip_tags($announcement->description) }}
                            </p>

                            <div class="admin-announcement-card-actions">
                                <a href="{{ $baseUrl }}?edit={{ $announcement->id }}#editor"
                                   class="btn btn-secondary btn-sm">
                                    Редактировать
                                </a>

                                <button type="button"
                                        class="btn btn-danger btn-sm"
                                        onclick="openDeleteAnnouncementModal(
                                            '{{ $announcement->id }}',
                                            '{{ e($announcement->title) }}'
                                        )">
                                    Удалить
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="announcements-empty">
                <div class="announcements-empty-icon">📢</div>

                <h4>Объявлений пока нет</h4>

                <p>Добавьте первое объявление через форму выше.</p>
            </div>
        @endif
    </div>

</section>

<div id="deleteAnnouncementModal" class="admin-delete-modal" style="display: none;">
    <div class="admin-delete-modal-box">
        <button type="button" class="admin-delete-modal-close" onclick="closeDeleteAnnouncementModal()">
            &times;
        </button>

        <div class="admin-delete-modal-icon">🗑</div>

        <h3>Удалить объявление?</h3>

        <p>
            Вы действительно хотите удалить:
            <br>
            <strong id="deleteAnnouncementTitle"></strong>
        </p>

        <div class="admin-delete-modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteAnnouncementModal()">
                Отмена
            </button>

            <form method="POST" id="deleteAnnouncementForm">
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
        const input = document.getElementById('announcementImageInput');
        const preview = document.getElementById('announcementImagePreview');
        const previewBox = document.getElementById('announcementImagePreviewBox');
        const cancel = document.getElementById('announcementImageCancel');
        const deleteModal = document.getElementById('deleteAnnouncementModal');

        input?.addEventListener('change', function () {
            const file = this.files[0];

            if (file) {
                preview.src = URL.createObjectURL(file);
                previewBox.style.display = 'block';
            }
        });

        cancel?.addEventListener('click', function () {
            input.value = '';
            preview.src = '';
            previewBox.style.display = 'none';
        });

        deleteModal?.addEventListener('click', function (event) {
            if (event.target === deleteModal) {
                closeDeleteAnnouncementModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && deleteModal && deleteModal.style.display === 'flex') {
                closeDeleteAnnouncementModal();
            }
        });
    });

    function openDeleteAnnouncementModal(id, title) {
        const modal = document.getElementById('deleteAnnouncementModal');
        const form = document.getElementById('deleteAnnouncementForm');
        const titleElement = document.getElementById('deleteAnnouncementTitle');

        form.action = '{{ $baseUrl }}/delete/' + id;
        titleElement.textContent = title;

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteAnnouncementModal() {
        const modal = document.getElementById('deleteAnnouncementModal');

        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
</script>

@endsection