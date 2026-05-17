@extends('layout')

@section('content')

<section class="admin-teachers-page">

    <div class="admin-teachers-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Админ-панель</span>
                <h3>Управление учителями</h3>
            </div>

            <a href="/admin/dashboard" class="section-link">
                ← Назад
            </a>
        </div>

        <p class="admin-teachers-hero-text">
            Здесь можно добавить педагога, изменить информацию о преподавателе,
            обновить фотографию или удалить запись из списка учителей.
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

    <div class="admin-teachers-editor mb-4" id="editor">
        <div class="admin-teachers-editor-head">
            <div>
                <span class="page-label">
                    {{ $editTeacher ? 'Редактирование' : 'Создание' }}
                </span>

                <h4>
                    {{ $editTeacher ? 'Редактировать учителя' : 'Добавить учителя' }}
                </h4>
            </div>

            @if($editTeacher)
                <a href="/admin/teachers" class="btn btn-secondary btn-sm">
                    + Новый учитель
                </a>
            @endif
        </div>

        <form method="POST"
              action="{{ $editTeacher ? '/admin/teachers/update/' . $editTeacher->id : '/admin/teachers/store' }}"
              enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="mb-3">
                        <label class="form-label">ФИО учителя</label>
                        <input type="text"
                               name="full_name"
                               class="form-control"
                               placeholder="Иванов Иван Иванович"
                               value="{{ old('full_name', $editTeacher->full_name ?? '') }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Предметы</label>
                        <textarea name="subjects"
                                  class="form-control"
                                  rows="9"
                                  placeholder="Математика, Алгебра, Геометрия"
                                  required>{{ old('subjects', $editTeacher->subjects ?? '') }}</textarea>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="admin-teachers-side">
                        @if($editTeacher && $editTeacher->photo)
                            <div class="mb-3">
                                <label class="form-label">Текущее фото</label>

                                <img src="{{ asset('storage/' . $editTeacher->photo) }}"
                                     class="admin-teacher-current-photo"
                                     alt="{{ $editTeacher->full_name }}">
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">
                                {{ $editTeacher ? 'Новое фото' : 'Фотография' }}
                            </label>

                            <input type="file"
                                   name="photo"
                                   id="photoInput"
                                   class="form-control"
                                   accept="image/*">

                            <small class="text-muted">
                                Рекомендуемый размер: 300×300px или вертикальное фото
                            </small>
                        </div>

                        <div id="teacherPreviewBox" class="admin-teacher-preview-box" style="display: none;">
                            <img id="teacherPreview" class="admin-teacher-preview" alt="Предпросмотр">

                            <button type="button" id="teacherCancelBtn" class="btn btn-secondary btn-sm mt-2">
                                Отменить фото
                            </button>
                        </div>

                        <div class="admin-teachers-actions">
                            <button type="submit" class="btn btn-primary">
                                {{ $editTeacher ? 'Обновить учителя' : 'Сохранить учителя' }}
                            </button>

                            @if($editTeacher)
                                <a href="/admin/teachers" class="btn btn-secondary">
                                    Отмена
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="admin-teachers-list">
        <div class="section-head">
            <div>
                <span class="page-label">Список</span>
                <h3>Педагогический состав</h3>
            </div>
        </div>

        @if($teachers->count() > 0)
            <div class="admin-teachers-grid">
                @foreach($teachers as $teacher)
                    <article class="admin-teacher-card">
                        @if($teacher->photo)
                            <div class="admin-teacher-photo-wrap">
                                <img src="{{ asset('storage/' . $teacher->photo) }}"
                                     class="admin-teacher-photo"
                                     alt="{{ $teacher->full_name }}">
                            </div>
                        @else
                            <div class="admin-teacher-photo-empty">
                                Нет фото
                            </div>
                        @endif

                        <div class="admin-teacher-card-body">
                            <h4>
                                {{ $teacher->full_name }}
                            </h4>

                            <p>
                                {{ \Illuminate\Support\Str::limit($teacher->subjects, 110) }}
                            </p>

                            <div class="admin-teacher-card-actions">
                                <a href="/admin/teachers?edit={{ $teacher->id }}#editor"
                                   class="btn btn-secondary btn-sm">
                                    Редактировать
                                </a>

                                <button type="button"
                                        class="btn btn-danger btn-sm"
                                        onclick="openDeleteTeacherModal(
                                            '{{ $teacher->id }}',
                                            '{{ e($teacher->full_name) }}'
                                        )">
                                    Удалить
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="admin-teachers-empty">
                <div class="admin-teachers-empty-icon">👩‍🏫</div>
                <h4>Учителя пока не добавлены</h4>
                <p>Добавьте первого педагога через форму выше.</p>
            </div>
        @endif
    </div>

</section>

<div id="deleteTeacherModal" class="admin-delete-modal" style="display: none;">
    <div class="admin-delete-modal-box">
        <button type="button" class="admin-delete-modal-close" onclick="closeDeleteTeacherModal()">
            &times;
        </button>

        <div class="admin-delete-modal-icon">
            🗑
        </div>

        <h3>Удалить учителя?</h3>

        <p>
            Вы действительно хотите удалить запись:
            <br>
            <strong id="deleteTeacherTitle"></strong>
        </p>

        <div class="admin-delete-modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteTeacherModal()">
                Отмена
            </button>

            <form method="POST" id="deleteTeacherForm">
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
        const input = document.getElementById('photoInput');
        const preview = document.getElementById('teacherPreview');
        const previewBox = document.getElementById('teacherPreviewBox');
        const cancelBtn = document.getElementById('teacherCancelBtn');

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

        const deleteModal = document.getElementById('deleteTeacherModal');

        deleteModal?.addEventListener('click', function (event) {
            if (event.target === deleteModal) {
                closeDeleteTeacherModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && deleteModal && deleteModal.style.display === 'flex') {
                closeDeleteTeacherModal();
            }
        });
    });

    function openDeleteTeacherModal(id, title) {
        const modal = document.getElementById('deleteTeacherModal');
        const form = document.getElementById('deleteTeacherForm');
        const titleElement = document.getElementById('deleteTeacherTitle');

        form.action = '/admin/teachers/delete/' + id;
        titleElement.textContent = title;

        modal.style.display = 'flex';
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteTeacherModal() {
        const modal = document.getElementById('deleteTeacherModal');

        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
    }
</script>

@endsection