@extends('layout')

@section('content')
@php
    $selectedClass = $selectedClass ?? $class ?? '';
    $selectedDay = $selectedDay ?? request('day');
    $weekStartDate = $weekStartDate ?? \Carbon\Carbon::parse($weekStart)->format('Y-m-d');
    $weekStartCarbon = \Carbon\Carbon::parse($weekStartDate);
    $weekStartView = $weekStartCarbon->format('d.m.Y');
    $weekEndView = $weekStartCarbon->copy()->addDays(4)->format('d.m.Y');
    $visibleDays = $selectedDay ? [$selectedDay] : $days;
    // Ограничиваем уроки 1-7
    $lessons = range(1, 7);
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
            Выберите класс, учебную неделю и при необходимости отдельный день недели для просмотра расписания занятий.
        </p>
    </div>

    <form method="GET" action="/schedule" class="row g-3 align-items-end mb-4">
        <div class="col-lg-4">
            <label class="form-label">Класс</label>
            <select name="class" id="classSelect" class="form-select">
                <option value="">Выберите класс</option>
                @foreach($classes as $classOption)
                    <option value="{{ $classOption }}" {{ (string) $selectedClass === (string) $classOption ? 'selected' : '' }}>
                        {{ $classOption }} класс
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-4">
            <label class="form-label">День недели</label>
            <select name="day" id="daySelect" class="form-select">
                <option value="">Все дни</option>
                @foreach($days as $day)
                    <option value="{{ $day }}" {{ $selectedDay === $day ? 'selected' : '' }}>{{ $day }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3">
            <label class="form-label">Учебная неделя</label>
            <input type="date" name="week_start_date" class="form-control" value="{{ $weekStartDate }}">
          
        </div>

        <div class="col-lg-1">
            <button type="submit" class="btn btn-primary w-100">ОК</button>
        </div>
    </form>

    <div class="schedule-summary mb-4">
        <div><strong>Неделя:</strong> {{ $weekStartView }} — {{ $weekEndView }}</div>
        <div><strong>Класс:</strong> {{ $selectedClass ? $selectedClass . ' класс' : 'не выбран' }}</div>
        <div><strong>День:</strong> {{ $selectedDay ?: 'все дни' }}</div>
    </div>

    @if($selectedClass)
        <div class="table-responsive">
            <table class="table table-bordered align-middle schedule-table">
                <thead>
                    <tr>
                        <th style="width: 70px; text-align: center;">Урок</th>
                        @foreach($visibleDays as $day)
                            <th style="text-align: center;">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($lessons as $lesson)
                        <tr>
                            <th style="text-align: center; width: 70px;">{{ $lesson }}</th>
                            @foreach($visibleDays as $day)
                                @php
                                    $cell = $schedule[$day][$lesson] ?? ['subject' => ''];
                                    $subject = is_array($cell) ? ($cell['subject'] ?? '') : (string) $cell;
                                @endphp
                                <td style="text-align: center;">
                                    @if($subject)
                                        <div class="schedule-subject">{{ $subject }}</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-secondary">
            Выберите класс. После выбора класса появится расписание уроков.
        </div>
    @endif
</section>

<style>
.schedule-summary {
    display: flex;
    flex-wrap: wrap;
    gap: 12px 24px;
}

.schedule-subject {
    font-weight: 600;
}

.schedule-page .table-responsive {
    overflow-x: auto;
}

.schedule-table {
    min-width: 660px;
}

.schedule-table thead th {
    background: #1557b0 !important;
    color: #ffffff !important;
    white-space: nowrap;
    text-align: center;
    vertical-align: middle;
    padding: 12px 8px;
}

.schedule-table td,
.schedule-table th {
    color: #162033;
    vertical-align: middle;
    padding: 10px 8px;
}

.schedule-table tbody th {
    background: #f8fbff;
    font-weight: 700;
    color: #0f3f86;
    font-size: 16px;
}

.schedule-table td {
    text-align: center;
}

.schedule-page .form-label,
.schedule-page .form-control,
.schedule-page .form-select {
    color: #162033;
}

.schedule-hero-text,
.schedule-summary {
    overflow-wrap: anywhere;
}

@media (max-width: 768px) {
    .schedule-table {
        min-width: 500px;
    }
    
    .schedule-table thead th,
    .schedule-table td,
    .schedule-table th {
        padding: 8px 6px;
        font-size: 14px;
    }
}
</style>
@endsection