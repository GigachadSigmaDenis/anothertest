@extends('layout')

@section('content')
@php
    use App\Models\ScheduleTemplate;
    
    // Получаем статус шаблонов для всех дней
    $templateStatus = [];
    foreach ($days as $day) {
        $templateStatus[$day] = ScheduleTemplate::hasTemplate($class, $day);
    }
@endphp

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
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
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

    <div class="admin-schedule-editor mb-4" id="editor">
        <div class="admin-schedule-editor-head">
            <div>
                <span class="page-label">Редактор</span>
                <h4>Добавить или изменить день</h4>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <form method="POST" action="/admin/schedule/apply-template" class="d-inline" id="applyTemplateForm">
                    @csrf
                    <input type="hidden" name="class" value="{{ $class }}">
                    <input type="hidden" name="day" id="applyTemplateDay" value="{{ $selectedDay }}">
                    <input type="hidden" name="week_start_date" value="{{ $weekStart->format('Y-m-d') }}">
                    <button type="button" class="btn btn-outline-primary" 
                            id="applyTemplateBtn"
                            {{ $hasTemplate ? '' : 'disabled' }}
                            title="{{ $hasTemplate ? 'Вставить расписание из шаблона' : 'Шаблон не найден для этого класса и дня' }}"
                            onclick="applyTemplate()">
                        📋 Шаблон
                    </button>
                </form>

                <form method="POST" action="/admin/schedule/save-template" class="d-inline" id="saveTemplateForm">
                    @csrf
                    <input type="hidden" name="class" value="{{ $class }}">
                    <input type="hidden" name="day" id="saveTemplateDay" value="{{ $selectedDay }}">
                    <input type="hidden" name="week_start_date" value="{{ $weekStart->format('Y-m-d') }}">
                    <button type="button" class="btn btn-outline-secondary" onclick="saveTemplate()">
                        💾 Сохранить как шаблон
                    </button>
                </form>
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
                                    <option value="{{ $day }}" {{ $selectedDay == $day ? 'selected' : '' }}>
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

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                Сохранить день
                            </button>
                            <button type="button" class="btn btn-outline-danger" 
                                    onclick="openConfirmModal('clearForm', 'Очистить поля?', 'Все поля с предметами будут очищены.')">
                                Очистить
                            </button>
                        </div>
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
                                       placeholder="Предмет для {{ $lesson }} урока"
                                       value="{{ old('lessons.' . $lesson, $schedule[$selectedDay][$lesson]['subject'] ?? '') }}">
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
                <article class="admin-schedule-day-card">
                    <div class="admin-schedule-day-head">
                        <h4>{{ $day }}</h4>

                        <div class="admin-schedule-day-actions">
                            <span class="badge bg-info text-white me-2">
                                {{ $templateStatus[$day] ? 'Шаблон есть' : 'Нет шаблона' }}
                            </span>

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
                                    @if(!empty($schedule[$day][$lesson]['subject']))
                                        {{ $schedule[$day][$lesson]['subject'] }}
                                    @else
                                        —
                                    @endif
                                </strong>
                            </div>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </div>

</section>

<!-- Модальное окно подтверждения -->
<div id="confirmModal" class="admin-confirm-modal" style="display: none;">
    <div class="admin-confirm-modal-box">
        <button type="button" class="admin-confirm-modal-close" onclick="closeConfirmModal()">
            &times;
        </button>

        <div class="admin-confirm-modal-icon" id="confirmModalIcon">
            ⚠️
        </div>

        <h3 id="confirmModalTitle">Подтверждение</h3>

        <p id="confirmModalMessage"></p>

        <div class="admin-confirm-modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeConfirmModal()">
                Отмена
            </button>

            <button type="button" class="btn btn-primary" id="confirmModalConfirmBtn" onclick="confirmAction()">
                Подтвердить
            </button>
        </div>
    </div>
</div>

<!-- Модальное окно удаления расписания -->
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
                <input type="hidden" name="week_start_date" id="deleteScheduleWeek">

                <button type="submit" class="btn btn-danger">
                    Удалить
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.admin-schedule-hero {
    margin-bottom: 24px;
}

.admin-schedule-hero-text {
    color: #6c757d;
    max-width: 700px;
    margin-top: 8px;
}

.admin-schedule-filter {
    background: #ffffff;
    border: 1px solid #dbe3ef;
    border-radius: 20px;
    padding: 22px;
    box-shadow: 0 8px 24px rgba(15, 63, 134, 0.06);
}

.admin-schedule-filter-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.admin-schedule-editor {
    background: #ffffff;
    border: 1px solid #dbe3ef;
    border-radius: 20px;
    padding: 22px;
    box-shadow: 0 8px 24px rgba(15, 63, 134, 0.06);
}

.admin-schedule-editor-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    padding-bottom: 16px;
    margin-bottom: 20px;
    border-bottom: 1px solid #dbe3ef;
}

.admin-schedule-editor-head h4 {
    margin: 0;
    color: #0f3f86;
}

.admin-schedule-week-badge {
    padding: 6px 16px;
    border-radius: 999px;
    background: #eef5ff;
    color: #0f3f86;
    font-weight: 600;
    font-size: 14px;
    white-space: nowrap;
}

.admin-schedule-side {
    padding: 16px;
    background: #f8fbff;
    border-radius: 16px;
    border: 1px solid #dbe3ef;
}

.admin-schedule-lessons {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.admin-schedule-lesson-row {
    display: flex;
    align-items: center;
    gap: 12px;
}

.admin-schedule-lesson-number {
    min-width: 48px;
    font-weight: 600;
    color: #0f3f86;
    text-align: center;
}

.admin-schedule-lesson-row .form-control {
    flex: 1;
}

.admin-schedule-list {
    background: #ffffff;
    border: 1px solid #dbe3ef;
    border-radius: 20px;
    padding: 22px;
    box-shadow: 0 8px 24px rgba(15, 63, 134, 0.06);
}

.admin-schedule-days {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-top: 16px;
}

.admin-schedule-day-card {
    border: 1px solid #dbe3ef;
    border-radius: 16px;
    padding: 16px;
    background: #f8fbff;
}

.admin-schedule-day-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 12px;
}

.admin-schedule-day-head h4 {
    margin: 0;
    color: #0f3f86;
}

.admin-schedule-day-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}

.admin-schedule-day-lessons {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 6px;
}

.admin-schedule-day-lesson {
    display: flex;
    justify-content: space-between;
    padding: 6px 12px;
    background: #ffffff;
    border-radius: 8px;
    border: 1px solid #eef5ff;
    font-size: 14px;
}

.admin-schedule-day-lesson span {
    color: #6c757d;
}

.admin-schedule-day-lesson strong {
    color: #162033;
}

.schedule-help {
    display: block;
    margin-top: 4px;
    color: #6c757d;
    font-size: 12px;
}

/* Модальное окно подтверждения */
.admin-confirm-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 2150;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: rgba(15, 32, 51, 0.58);
}

.admin-confirm-modal-box {
    position: relative;
    width: min(100%, 440px);
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

.admin-confirm-modal-box h3 {
    color: #0f3f86;
    margin-bottom: 12px;
}

.admin-confirm-modal-box p {
    color: #162033;
    overflow-wrap: anywhere;
    margin-bottom: 0;
}

.admin-confirm-modal-close {
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

.admin-confirm-modal-icon {
    font-size: 48px;
    margin-bottom: 12px;
}

.admin-confirm-modal-actions {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
}

.admin-confirm-modal-actions .btn {
    min-width: 100px;
}

/* Модальное окно удаления */
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

@media (max-width: 768px) {
    .admin-schedule-editor-head {
        flex-direction: column;
        align-items: flex-start;
    }

    .admin-schedule-day-head {
        flex-direction: column;
        align-items: flex-start;
    }

    .admin-schedule-day-actions {
        width: 100%;
        justify-content: flex-start;
    }

    .admin-schedule-day-lessons {
        grid-template-columns: 1fr;
    }

    .admin-schedule-filter-actions {
        flex-direction: column;
    }

    .admin-schedule-filter-actions .btn {
        width: 100%;
    }
}
</style>

<script>
    let confirmCallback = null;
    let confirmFormId = null;

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

    function applyTemplate() {
        const day = document.getElementById('editorDay').value;
        const classValue = document.querySelector('input[name="class"]').value;
        const weekStart = document.getElementById('editorWeekStart').value;
        
        // Обновляем скрытые поля
        document.getElementById('applyTemplateDay').value = day;
        
        openConfirmModal('applyTemplateForm', 'Применить шаблон?', 
            'Будет вставлено расписание из шаблона для ' + classValue + ' класса на ' + day + '. Текущее расписание будет заменено.');
    }

    function saveTemplate() {
        const day = document.getElementById('editorDay').value;
        const classValue = document.querySelector('input[name="class"]').value;
        
        // Обновляем скрытые поля
        document.getElementById('saveTemplateDay').value = day;
        
        openConfirmModal('saveTemplateForm', 'Сохранить как шаблон?', 
            'Текущее расписание для ' + classValue + ' класса на ' + day + ' будет сохранено как шаблон.');
    }

    function openConfirmModal(formId, title, message, icon = '⚠️') {
        const modal = document.getElementById('confirmModal');
        document.getElementById('confirmModalIcon').textContent = icon;
        document.getElementById('confirmModalTitle').textContent = title;
        document.getElementById('confirmModalMessage').textContent = message;
        
        confirmFormId = formId;
        
        modal.style.display = 'flex';
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        confirmFormId = null;
        confirmCallback = null;
    }

    function confirmAction() {
        if (confirmFormId === 'clearForm') {
            // Очищаем все поля
            document.querySelectorAll('#scheduleEditorForm input[name^=lessons]').forEach(el => el.value = '');
            closeConfirmModal();
        } else if (confirmFormId) {
            // Отправляем форму
            document.getElementById(confirmFormId).submit();
            closeConfirmModal();
        } else if (confirmCallback) {
            confirmCallback();
            closeConfirmModal();
        }
    }

    function fillScheduleEditor(day, lessons) {
        document.getElementById('editorDay').value = day;

        // Обновляем скрытые поля для шаблонов
        document.getElementById('applyTemplateDay').value = day;
        document.getElementById('saveTemplateDay').value = day;

        for (let i = 1; i <= 8; i++) {
            const input = document.getElementById('lessonInput' + i);
            if (input) {
                input.value = lessons[i]?.subject || '';
            }
        }

        // Обновляем статус кнопки "Шаблон"
        const classValue = document.querySelector('input[name="class"]').value;
        fetch('/zam/schedule/check-template?class=' + encodeURIComponent(classValue) + '&day=' + encodeURIComponent(day))
            .then(response => response.json())
            .then(data => {
                const btn = document.getElementById('applyTemplateBtn');
                if (data.hasTemplate) {
                    btn.disabled = false;
                    btn.title = 'Вставить расписание из шаблона';
                } else {
                    btn.disabled = true;
                    btn.title = 'Шаблон не найден для этого класса и дня';
                }
            })
            .catch(() => {});

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
        const confirmModal = document.getElementById('confirmModal');

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

        confirmModal?.addEventListener('click', function (event) {
            if (event.target === confirmModal) {
                closeConfirmModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                if (confirmModal && confirmModal.style.display === 'flex') {
                    closeConfirmModal();
                }
                if (deleteModal && deleteModal.style.display === 'flex') {
                    closeDeleteScheduleModal();
                }
            }
        });

        // Автоматическое обновление статуса шаблона при смене дня
        document.getElementById('editorDay')?.addEventListener('change', function() {
            const day = this.value;
            const classValue = document.querySelector('input[name="class"]').value;
            
            // Обновляем скрытые поля
            document.getElementById('applyTemplateDay').value = day;
            document.getElementById('saveTemplateDay').value = day;
            
            fetch('/admin/schedule/check-template?class=' + encodeURIComponent(classValue) + '&day=' + encodeURIComponent(day))
                .then(response => response.json())
                .then(data => {
                    const btn = document.getElementById('applyTemplateBtn');
                    if (data.hasTemplate) {
                        btn.disabled = false;
                        btn.title = 'Вставить расписание из шаблона';
                    } else {
                        btn.disabled = true;
                        btn.title = 'Шаблон не найден для этого класса и дня';
                    }
                })
                .catch(() => {});
        });
    });
</script>

@endsection