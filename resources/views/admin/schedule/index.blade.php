@extends('layout')

@section('content')

<section class="admin-schedule-page">

    <div class="admin-schedule-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Админ-панель</span>
                <h3>Редактор расписания</h3>
            </div>

            <a href="/admin/dashboard" class="section-link">
                ← Назад
            </a>
        </div>

        <p class="admin-schedule-hero-text">
            Здесь можно редактировать расписание уроков по классам, учебным неделям и дням.
            Дата недели автоматически приводится к понедельнику.
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

    <div class="admin-schedule-filter mb-4">
        <form method="GET" id="scheduleFilterForm">
            <div class="row g-3">
                <div class="col-lg-4">
                    <label class="form-label">Класс</label>

                    <input type="text"
                           name="class"
                           class="form-control"
                           value="{{ $class }}"
                           placeholder="Например: 5, 6, 7А">
                </div>

                <div class="col-lg-4">
                    <label class="form-label">Учебная неделя</label>

                    <input type="date"
                           name="week_start"
                           id="filterWeekStart"
                           class="form-control"
                           value="{{ $weekStart->format('Y-m-d') }}">

                    <small class="schedule-help">
                        Можно выбрать любой день — сайт сам перенесёт дату на понедельник недели.
                    </small>
                </div>

                <div class="col-lg-4">
                    <label class="form-label">День недели</label>

                    <select name="day" class="form-select">
                        <option value="">Все дни</option>
                        @foreach($days as $day)
                            <option value="{{ $day }}" {{ $selectedDay == $day ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <div class="admin-schedule-filter-actions">
                        <button type="submit" class="btn btn-primary">
                            Применить фильтр
                        </button>

                        <a href="/admin/schedule" class="btn btn-secondary">
                            Сбросить
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="admin-schedule-editor mb-4">
        <div class="admin-schedule-editor-head">
            <div>
                <span class="page-label">Редактор</span>
                <h4>Добавить или изменить день</h4>
            </div>

            <div class="admin-schedule-week-badge">
                {{ $weekStart->format('d.m.Y') }} — {{ $weekStart->copy()->addDays(4)->format('d.m.Y') }}
            </div>
        </div>

        <form method="POST" action="/admin/schedule/store" id="scheduleEditorForm">
            @csrf

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="admin-schedule-side">
                        <div class="mb-3">
                            <label class="form-label">Класс</label>
                            <input type="text"
                                   name="class"
                                   class="form-control"
                                   value="{{ $class }}"
                                   placeholder="Например: 5"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">День недели</label>
                            <select name="day" id="editorDay" class="form-select" required>
                                @foreach($days as $day)
                                    <option value="{{ $day }}">
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Начало недели</label>
                            <input type="date"
                                   name="week_start_date"
                                   id="editorWeekStart"
                                   class="form-control"
                                   value="{{ $weekStart->format('Y-m-d') }}"
                                   required>

                            <small class="schedule-help">
                                Сохраняется как понедельник выбранной недели.
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Сохранить день
                        </button>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="admin-schedule-lessons">
                        @foreach($lessons as $lesson)
                            <div class="admin-schedule-lesson-row">
                                <div class="admin-schedule-lesson-number">
                                    {{ $lesson }}
                                </div>

                                <input type="text"
                                       name="lessons[{{ $lesson }}]"
                                       id="lessonInput{{ $lesson }}"
                                       class="form-control"
                                       placeholder="Предмет для {{ $lesson }} урока">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="admin-schedule-list">
        <div class="section-head">
            <div>
                <span class="page-label">Расписание</span>
                <h3>{{ $class }} класс</h3>
            </div>

            <div class="admin-schedule-week-badge">
                {{ $weekStart->format('d.m.Y') }} — {{ $weekStart->copy()->addDays(4)->format('d.m.Y') }}
            </div>
        </div>

        <div class="admin-schedule-days">
            @foreach($days as $day)
                @if(!$selectedDay || $selectedDay == $day)
                    <article class="admin-schedule-day-card">
                        <div class="admin-schedule-day-head">
                            <h4>{{ $day }}</h4>

                            <div class="admin-schedule-day-actions">
                                <button type="button"
                                        class="btn btn-secondary btn-sm"
                                        onclick='fillScheduleEditor(
                                            @json($day),
                                            @json($schedule[$day] ?? [])
                                        )'>
                                    Редактировать
                                </button>

                                <button type="button"
                                        class="btn btn-danger btn-sm"
                                        onclick="openDeleteScheduleModal(
                                            '{{ $class }}',
                                            '{{ $day }}',
                                            '{{ $weekStart->format('Y-m-d') }}'
                                        )">
                                    Удалить
                                </button>
                            </div>
                        </div>

                        <div class="admin-schedule-day-lessons">
                            @foreach($lessons as $lesson)
                                <div class="admin-schedule-day-lesson">
                                    <span>{{ $lesson }} урок</span>

                                    <strong>
                                        @if(!empty($schedule[$day][$lesson]))
                                            {{ $schedule[$day][$lesson] }}
                                        @else
                                            —
                                        @endif
                                    </strong>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endif
            @endforeach
        </div>
    </div>

</section>

<div id="deleteScheduleModal" class="admin-delete-modal" style="display: none;">
    <div class="admin-delete-modal-box">
        <button type="button" class="admin-delete-modal-close" onclick="closeDeleteScheduleModal()">
            &times;
        </button>

        <div class="admin-delete-modal-icon">
            🗑
        </div>

        <h3>Удалить расписание?</h3>

        <p>
            Будет удалено расписание для:
            <br>
            <strong id="deleteScheduleTitle"></strong>
        </p>

        <div class="admin-delete-modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteScheduleModal()">
                Отмена
            </button>

            <form method="POST" action="/admin/schedule/delete-day" id="deleteScheduleForm">
                @csrf

                <input type="hidden" name="class" id="deleteScheduleClass">
                <input type="hidden" name="day" id="deleteScheduleDay">
                <input type="hidden" name="week_start" id="deleteScheduleWeek">

                <button type="submit" class="btn btn-danger">
                    Удалить
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function getMondayFromDate(dateValue) {
        const date = new Date(dateValue + 'T00:00:00');

        if (Number.isNaN(date.getTime())) {
            return null;
        }

        const day = date.getDay();
        const diff = day === 0 ? -6 : 1 - day;

        date.setDate(date.getDate() + diff);

        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const dayNumber = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${dayNumber}`;
    }

    function normalizeDateInputToMonday(input) {
        if (!input || !input.value) return;

        const monday = getMondayFromDate(input.value);

        if (monday) {
            input.value = monday;
        }
    }

    function fillScheduleEditor(day, lessons) {
        document.getElementById('editorDay').value = day;

        for (let i = 1; i <= 7; i++) {
            const input = document.getElementById('lessonInput' + i);
            input.value = lessons[i] || '';
        }

        document.getElementById('editor').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    function openDeleteScheduleModal(className, day, weekStart) {
        const modal = document.getElementById('deleteScheduleModal');

        document.getElementById('deleteScheduleClass').value = className;
        document.getElementById('deleteScheduleDay').value = day;
        document.getElementById('deleteScheduleWeek').value = weekStart;

        document.getElementById('deleteScheduleTitle').textContent =
            className + ' класс, ' + day + ', неделя с ' + weekStart;

        modal.style.display = 'flex';
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteScheduleModal() {
        const modal = document.getElementById('deleteScheduleModal');

        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const filterWeekStart = document.getElementById('filterWeekStart');
        const editorWeekStart = document.getElementById('editorWeekStart');
        const deleteModal = document.getElementById('deleteScheduleModal');

        filterWeekStart?.addEventListener('change', function () {
            normalizeDateInputToMonday(this);
        });

        editorWeekStart?.addEventListener('change', function () {
            normalizeDateInputToMonday(this);
        });

        deleteModal?.addEventListener('click', function (event) {
            if (event.target === deleteModal) {
                closeDeleteScheduleModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && deleteModal && deleteModal.style.display === 'flex') {
                closeDeleteScheduleModal();
            }
        });
    });
</script>

@endsection