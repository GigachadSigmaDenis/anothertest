@extends('layout')

@section('content')

@php
    $selectedDay = request('day');
    $weekStartFormatted = \Carbon\Carbon::parse($weekStart)->format('Y-m-d');
    $weekStartView = \Carbon\Carbon::parse($weekStart)->format('d.m.Y');
    $weekEndView = \Carbon\Carbon::parse($weekStart)->copy()->addDays(4)->format('d.m.Y');
@endphp

<section class="schedule-page">

    <div class="schedule-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Расписание</span>
                <h3>Расписание уроков</h3>
            </div>
        </div>

        <p class="schedule-hero-text">
            Выберите класс, учебную неделю и при необходимости отдельный день недели
            для просмотра расписания занятий.
        </p>
    </div>

    <div class="schedule-filter-card mb-4">
        <div class="row g-3">
            <div class="col-lg-4">
                <label class="form-label">Класс</label>

                <select id="classSelect" class="form-select">
                    <option value="">Выберите класс</option>

                    @for($i = 1; $i <= 9; $i++)
                        <option value="{{ $i }}" {{ $class == $i ? 'selected' : '' }}>
                            {{ $i }} класс
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-lg-4">
                <label class="form-label">День недели</label>

                <select id="daySelect" class="form-select">
                    <option value="">Все дни</option>

                    @foreach($days as $day)
                        <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>
                            {{ $day }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4">
                <label class="form-label">Учебная неделя</label>

                <input type="date"
                    id="weekStart"
                    class="form-control"
                    value="{{ $weekStartFormatted }}">

                <small class="schedule-help">
                    Выберите понедельник нужной недели. Если выбрать другой день, сайт автоматически перенесёт дату на понедельник этой недели.
                </small>
            </div>
        </div>
    </div>

    <div class="schedule-summary mb-4">
        <div class="schedule-summary-item">
            <span>Неделя</span>
            <strong>{{ $weekStartView }} — {{ $weekEndView }}</strong>
        </div>

        <div class="schedule-summary-item">
            <span>Класс</span>
            <strong>
                @if($class)
                    {{ $class }} класс
                @else
                    Не выбран
                @endif
            </strong>
        </div>

        <div class="schedule-summary-item">
            <span>День</span>
            <strong>
                @if(request('day'))
                    {{ request('day') }}
                @else
                    Все дни
                @endif
            </strong>
        </div>
    </div>

    @if($class)
        <div class="schedule-table-card">
            <div class="table-responsive">
                <table class="table schedule-table align-middle">
                    <thead>
                        <tr>
                            <th class="lesson-number-col">Урок</th>

                            @foreach($days as $day)
                                @if(!$selectedDay || $selectedDay == $day)
                                    <th>{{ $day }}</th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $lessonNumbers = [1, 2, 3, 4, 5, 6, 7];
                        @endphp

                        @foreach($lessonNumbers as $lesson)
                            <tr>
                                <td class="lesson-number">
                                    {{ $lesson }}
                                </td>

                                @foreach($days as $day)
                                    @if(!$selectedDay || $selectedDay == $day)
                                        <td class="subject-cell">
                                            @php
                                                $subject = $data[$lesson][$day] ?? '-';
                                            @endphp

                                            @if($subject != '-')
                                                <span class="subject-badge">
                                                    {{ $subject }}
                                                </span>
                                            @else
                                                <span class="subject-empty">
                                                    —
                                                </span>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(empty($data))
                <div class="schedule-empty mt-4">
                    <div class="schedule-empty-icon">📅</div>
                    <h4>Расписание пока не добавлено</h4>
                    <p>Для выбранного класса расписание ещё не опубликовано.</p>
                </div>
            @endif
        </div>
    @else
        <div class="schedule-empty">
            <div class="schedule-empty-icon">📚</div>
            <h4>Выберите класс</h4>
            <p>После выбора класса появится расписание уроков.</p>
        </div>
    @endif

</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const classSelect = document.getElementById('classSelect');
        const daySelect = document.getElementById('daySelect');
        const weekStartInput = document.getElementById('weekStart');

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

        function buildScheduleUrl() {
            const classValue = classSelect.value;
            const dayValue = daySelect.value;
            const weekValue = weekStartInput.value;

            if (!classValue) {
                window.location.href = '/schedule';
                return;
            }

            let url = '/schedule?class=' + encodeURIComponent(classValue);

            if (weekValue) {
                const monday = getMondayFromDate(weekValue);

                if (monday) {
                    url += '&week_start=' + encodeURIComponent(monday);
                }
            }

            if (dayValue) {
                url += '&day=' + encodeURIComponent(dayValue);
            }

            window.location.href = url;
        }

        classSelect?.addEventListener('change', function () {
            if (!this.value) {
                window.location.href = '/schedule';
                return;
            }

            buildScheduleUrl();
        });

        daySelect?.addEventListener('change', function () {
            buildScheduleUrl();
        });

        weekStartInput?.addEventListener('change', function () {
            const monday = getMondayFromDate(this.value);

            if (!monday) return;

            if (this.value !== monday) {
                this.value = monday;
            }

            buildScheduleUrl();
        });

        weekStartInput?.addEventListener('blur', function () {
            const monday = getMondayFromDate(this.value);

            if (monday) {
                this.value = monday;
            }
        });
    });
</script>

@endsection