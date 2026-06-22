@extends('layout')

@section('content')
<section class="admin-teachers-page">
    <div class="admin-teachers-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Админ-панель</span>
                <h3>Управление учителями</h3>
            </div>
            <a href="/admin/dashboard" class="section-link">← Назад</a>
        </div>
        <p class="admin-teachers-hero-text">
            У каждого учителя предметы вводятся отдельными строками. На сайте они отображаются отдельными элементами.
            Предметы необязательны — можно добавить учителя без предметов.
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
                <span class="page-label">{{ $editTeacher ? 'Редактирование' : 'Создание' }}</span>
                <h4>{{ $editTeacher ? 'Редактировать учителя' : 'Добавить учителя' }}</h4>
            </div>
            @if($editTeacher)
                <a href="/admin/teachers" class="btn btn-secondary btn-sm">Отменить</a>
            @endif
        </div>

        @php
            $subjectValues = old('subjects', $editTeacher ? $editTeacher->subject_list : ['']);
            if (!is_array($subjectValues)) {
                $subjectValues = [$subjectValues];
            }
            if (count($subjectValues) === 0) {
                $subjectValues = [''];
            }
        @endphp

        <form method="POST"
              enctype="multipart/form-data"
              action="{{ $editTeacher ? '/admin/teachers/update/' . $editTeacher->id : '/admin/teachers/store' }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">ФИО учителя <span class="text-danger">*</span></label>
                    <input type="text"
                           name="full_name"
                           class="form-control"
                           value="{{ old('full_name', $editTeacher->full_name ?? '') }}"
                           placeholder="Иванов Иван Иванович"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Фотография</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                    @if($editTeacher && $editTeacher->photo)
                        <small class="text-muted d-block mt-1">Текущее фото: {{ $editTeacher->photo }}</small>
                    @endif
                </div>

                <div class="col-md-12">
                    <label class="form-label">Предметы (необязательно)</label>
                    <div id="subjectsEditor" class="d-flex flex-column gap-2">
                        @foreach($subjectValues as $subject)
                            <div class="input-group subject-row">
                                <input type="text"
                                       name="subjects[]"
                                       class="form-control"
                                       value="{{ $subject }}"
                                       placeholder="Например: Математика">
                                <button type="button" class="btn btn-outline-danger" onclick="removeSubjectRow(this)">Удалить</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addSubjectRow()">
                        + Добавить предмет
                    </button>
                    <small class="text-muted d-block mt-2">
                        Каждый предмет вводится отдельно. Если учитель не ведёт предметы — оставьте поле пустым.
                    </small>
                </div>
            </div>

            <div class="mt-3 d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-primary">
                    {{ $editTeacher ? 'Сохранить изменения' : 'Добавить учителя' }}
                </button>
                @if($editTeacher)
                    <a href="/admin/teachers" class="btn btn-outline-secondary">Не редактировать</a>
                @endif
            </div>
        </form>
    </div>

    <div class="admin-teachers-list">
        <h4 class="mb-3">Список учителей</h4>

        @if($teachers->count())
            <div class="table-responsive admin-table-scroll">
                <table class="table table-striped align-middle admin-teachers-table">
                    <thead>
                        <tr>
                            <th>Учитель</th>
                            <th>Предметы</th>
                            <th>Фото</th>
                            <th class="text-end">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachers as $teacher)
                            @php
                                $teacherName = $teacher->full_name ?: $teacher->name;
                            @endphp
                            <tr>
                                <td class="admin-teacher-name-cell">{{ $teacherName }}</td>
                                <td>
                                    <div class="admin-teacher-subjects">
                                        @forelse($teacher->subject_list as $subject)
                                            <span class="badge bg-light text-dark border">{{ $subject }}</span>
                                        @empty
                                            <span class="text-muted">Не указаны</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    @if($teacher->photo)
                                        <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacherName }}" class="admin-teacher-avatar">
                                    @else
                                        <span class="text-muted">Нет фото</span>
                                    @endif
                                </td>
                                <td class="text-end admin-teacher-actions">
                                    <a href="/admin/teachers?edit={{ $teacher->id }}#editor" class="btn btn-sm btn-outline-primary">Редактировать</a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="openDeleteTeacherModal({{ $teacher->id }}, @js($teacherName))">
                                        Удалить
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-secondary">Учителя пока не добавлены.</div>
        @endif
    </div>
</section>

<div id="deleteTeacherModal" class="admin-delete-modal" aria-hidden="true">
    <div class="admin-delete-modal-box" role="dialog" aria-modal="true" aria-labelledby="deleteTeacherTitleText">
        <button type="button" class="admin-delete-modal-close" onclick="closeDeleteTeacherModal()" aria-label="Закрыть окно">&times;</button>
        <h3 id="deleteTeacherTitleText">Удалить учителя?</h3>
        <p>
            Вы действительно хотите удалить запись:<br>
            <strong id="deleteTeacherTitle"></strong>
        </p>
        <div class="admin-delete-modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteTeacherModal()">Отмена</button>
            <form method="POST" id="deleteTeacherForm">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Удалить</button>
            </form>
        </div>
    </div>
</div>

<style>
.admin-teachers-editor,
.admin-teachers-list {
    background: #ffffff;
    color: #162033;
    border: 1px solid #dbe3ef;
    border-radius: 20px;
    box-shadow: 0 8px 24px rgba(15, 63, 134, 0.08);
    padding: 22px;
}

.admin-teachers-editor-head {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: flex-start;
    margin-bottom: 18px;
}

.admin-table-scroll {
    overflow-x: auto;
}

.admin-teachers-table {
    min-width: 760px;
}

.admin-teachers-table thead th {
    color: #ffffff !important;
    background: #1557b0 !important;
    white-space: nowrap;
}

.admin-teacher-name-cell {
    max-width: 260px;
    overflow-wrap: anywhere;
    font-weight: 700;
}

.admin-teacher-subjects {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.admin-teacher-avatar {
    width: 48px;
    height: 48px;
    object-fit: cover;
    object-position: center center;
    border-radius: 50%;
    border: 1px solid #dbe3ef;
}

.admin-teacher-actions {
    white-space: nowrap;
}

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

.admin-delete-modal.is-open {
    display: flex;
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
}

.admin-delete-modal-actions {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
}
</style>

<script>
function addSubjectRow(value = '') {
    const editor = document.getElementById('subjectsEditor');
    const row = document.createElement('div');
    row.className = 'input-group subject-row';
    row.innerHTML = `
        <input type="text" name="subjects[]" class="form-control" value="${escapeHtml(value)}" placeholder="Например: Математика">
        <button type="button" class="btn btn-outline-danger" onclick="removeSubjectRow(this)">Удалить</button>
    `;
    editor.appendChild(row);
    row.querySelector('input').focus();
}

function removeSubjectRow(button) {
    const rows = document.querySelectorAll('.subject-row');
    if (rows.length === 1) {
        rows[0].querySelector('input').value = '';
        return;
    }

    button.closest('.subject-row').remove();
}

function openDeleteTeacherModal(id, title) {
    const modal = document.getElementById('deleteTeacherModal');
    const form = document.getElementById('deleteTeacherForm');
    const titleElement = document.getElementById('deleteTeacherTitle');

    form.action = '/admin/teachers/delete/' + encodeURIComponent(String(id));
    titleElement.textContent = title;
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
}

function closeDeleteTeacherModal() {
    const modal = document.getElementById('deleteTeacherModal');
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
}

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('deleteTeacherModal');

    if (!modal) return;

    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeDeleteTeacherModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeDeleteTeacherModal();
        }
    });
});
</script>
@endsection