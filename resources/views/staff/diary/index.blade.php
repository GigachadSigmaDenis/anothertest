@extends('layout')

@section('content')

@php
    $assignmentsByLesson = [];

    foreach ($assignments as $assignment) {
        $assignmentsByLesson[$assignment->lesson_number] = $assignment;
    }
@endphp

<section class="admin-diary-page">

    <div class="admin-diary-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">{{ $pageLabel }}</span>
                <h3>Редактор электронного дневника</h3>
            </div>

            <a href="{{ $backUrl }}" class="section-link">← Назад</a>
        </div>

        <p class="admin-diary-hero-text">
            Выберите класс, неделю и день. Затем нажмите на предмет из расписания,
            чтобы добавить задание, ссылки, файлы и оценки ученикам.
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

    <div class="admin-diary-filter mb-4">
        <form method="GET" class="row g-3">
            <div class="col-lg-4">
                <label class="form-label">Класс</label>
                <input type="text" name="class" class="form-control" value="{{ $class }}">
            </div>

            <div class="col-lg-4">
                <label class="form-label">Неделя</label>
                <input type="date" name="week_start" class="form-control" value="{{ $weekStart->format('Y-m-d') }}">
            </div>

            <div class="col-lg-4">
                <label class="form-label">День</label>
                <select name="day" class="form-select">
                    @foreach($days as $day)
                        <option value="{{ $day }}" {{ $selectedDay == $day ? 'selected' : '' }}>
                            {{ $day }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <button class="btn btn-primary">Показать</button>
                <a href="{{ $baseUrl }}" class="btn btn-secondary">Сбросить</a>
            </div>
        </form>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="admin-diary-lessons">
                <div class="section-head">
                    <div>
                        <span class="page-label">{{ $class }} класс</span>
                        <h3>{{ $selectedDay }}</h3>
                    </div>
                </div>

                @if($lessons->count() > 0)
                    @foreach($lessons as $lesson)
                        @php
                            $assignment = $assignmentsByLesson[$lesson->lesson_number] ?? null;

                            $assignmentData = null;

                            if ($assignment) {
                                $assignmentData = [
                                    'id' => $assignment->id,
                                    'text' => $assignment->text,
                                    'links' => $assignment->links->map(fn($l) => [
                                        'title' => $l->title,
                                        'url' => $l->url,
                                    ])->values(),
                                    'files' => $assignment->files->map(fn($f) => [
                                        'name' => $f->original_name,
                                        'path' => $f->path,
                                    ])->values(),
                                    'grades' => $assignment->grades->pluck('grade', 'user_id'),
                                ];
                            }
                        @endphp

                        <button type="button"
                                class="admin-diary-lesson-button"
                                onclick='selectDiaryLesson(
                                    @json($lesson->lesson_number),
                                    @json($lesson->subject),
                                    @json($assignmentData),
                                    @json($assignment?->id)
                                )'>
                            <span>{{ $lesson->lesson_number }} урок</span>
                            <strong>{{ $lesson->subject }}</strong>

                            @if($assignment)
                                <em>Задание есть</em>
                            @else
                                <em>Нет задания</em>
                            @endif
                        </button>
                    @endforeach
                @else
                    <div class="diary-empty">
                        <div class="diary-empty-icon">📅</div>
                        <h4>Расписание не найдено</h4>
                        <p>Сначала добавьте расписание на этот день.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-7">
            <div class="admin-diary-editor" id="diaryEditor">
                <div class="section-head">
                    <div>
                        <span class="page-label">Редактор</span>
                        <h3 id="editorTitle">Выберите предмет</h3>
                    </div>
                </div>

                <form method="POST" action="{{ $baseUrl }}/store" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="class" value="{{ $class }}">
                    <input type="hidden" name="week_start_date" value="{{ $weekStart->format('Y-m-d') }}">
                    <input type="hidden" name="day" value="{{ $selectedDay }}">
                    <input type="hidden" name="lesson_number" id="lessonNumberInput">
                    <input type="hidden" name="subject" id="subjectInput">

                    <div class="mb-3">
                        <label class="form-label">Текст задания</label>
                        <textarea name="text" id="diaryTextInput" rows="6" class="form-control" placeholder="Напишите домашнее задание или комментарий..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ссылки</label>

                        <div id="linksContainer"></div>

                        <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addDiaryLink()">
                            + Добавить ссылку
                        </button>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Файлы</label>
                        <input type="file" name="files[]" class="form-control" multiple>

                        <div id="currentFiles" class="admin-diary-current-files mt-2"></div>
                    </div>

                    <div class="admin-diary-students">
                        <h4>Оценки учеников</h4>

                        @if($students->count() > 0)
                            @foreach($students as $student)
                                <div class="admin-diary-student-row">
                                    <span>{{ $student->full_name }}</span>

                                    <select name="grades[{{ $student->id }}]" id="gradeStudent{{ $student->id }}" class="form-select form-select-sm">
                                        <option value="">Без оценки</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-info mb-0">
                                В этом классе пока нет учеников.
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary mt-3 w-100">
                        Сохранить задание и оценки
                    </button>
                </form>

                <form method="POST" id="deleteAssignmentForm" class="mt-2" style="display: none;">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger w-100">
                        Удалить задание
                    </button>
                </form>
            </div>
        </div>
    </div>

</section>

<script>
    let linkIndex = 0;

    function addDiaryLink(title = '', url = '') {
        const container = document.getElementById('linksContainer');

        const row = document.createElement('div');
        row.className = 'admin-diary-link-row';
        row.innerHTML = `
            <input type="text" name="links[${linkIndex}][title]" class="form-control" placeholder="Название ссылки" value="${escapeHtml(title)}">
            <input type="url" name="links[${linkIndex}][url]" class="form-control" placeholder="https://..." value="${escapeHtml(url)}">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">×</button>
        `;

        container.appendChild(row);
        linkIndex++;
    }

    function selectDiaryLesson(lessonNumber, subject, assignment, assignmentId) {
        document.getElementById('lessonNumberInput').value = lessonNumber;
        document.getElementById('subjectInput').value = subject;
        document.getElementById('editorTitle').textContent = lessonNumber + ' урок — ' + subject;

        document.getElementById('diaryTextInput').value = assignment?.text || '';

        const linksContainer = document.getElementById('linksContainer');
        linksContainer.innerHTML = '';
        linkIndex = 0;

        if (assignment?.links?.length) {
            assignment.links.forEach(function (link) {
                addDiaryLink(link.title || '', link.url || '');
            });
        } else {
            addDiaryLink();
        }

        const currentFiles = document.getElementById('currentFiles');
        currentFiles.innerHTML = '';

        if (assignment?.files?.length) {
            assignment.files.forEach(function (file) {
                const a = document.createElement('a');
                a.href = file.path;
                a.target = '_blank';
                a.textContent = file.name;
                currentFiles.appendChild(a);
            });
        }

        document.querySelectorAll('[id^="gradeStudent"]').forEach(function (select) {
            select.value = '';
        });

        if (assignment?.grades) {
            Object.keys(assignment.grades).forEach(function (userId) {
                const select = document.getElementById('gradeStudent' + userId);

                if (select) {
                    select.value = assignment.grades[userId] || '';
                }
            });
        }

        const deleteForm = document.getElementById('deleteAssignmentForm');

        if (assignmentId) {
            deleteForm.style.display = 'block';
            deleteForm.action = '{{ $baseUrl }}/delete/' + assignmentId;
        } else {
            deleteForm.style.display = 'none';
            deleteForm.action = '';
        }

        document.getElementById('diaryEditor').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    document.addEventListener('DOMContentLoaded', function () {
        addDiaryLink();
    });
</script>

@endsection