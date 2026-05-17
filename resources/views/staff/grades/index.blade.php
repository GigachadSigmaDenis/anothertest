@extends('layout')

@section('content')

<section class="staff-grades-page">

    <div class="staff-grades-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">{{ $pageLabel }}</span>
                <h3>Все оценки учеников</h3>
            </div>

            <a href="/profile" class="section-link">← В профиль</a>
        </div>

        <p class="staff-grades-hero-text">
            Здесь отображаются все выставленные оценки: ученик, предмет, день,
            учебная неделя, класс и оценка.
        </p>
    </div>

    <div class="staff-grades-filter mb-4">
        <form method="GET" class="row g-3">
            <div class="col-lg-3">
                <label class="form-label">Класс</label>
                <input type="text" name="class" class="form-control" value="{{ request('class') }}" placeholder="Например: 5">
            </div>

            <div class="col-lg-3">
                <label class="form-label">Неделя</label>
                <input type="date" name="week_start" class="form-control" value="{{ request('week_start') }}">
            </div>

            <div class="col-lg-3">
                <label class="form-label">День</label>
                <select name="day" class="form-select">
                    <option value="">Все дни</option>
                    @foreach($days as $day)
                        <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>
                            {{ $day }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-3">
                <label class="form-label">Предмет</label>
                <input type="text" name="subject" class="form-control" value="{{ request('subject') }}" placeholder="Например: Математика">
            </div>

            <div class="col-12">
                <button class="btn btn-primary">Применить</button>
                <a href="{{ $baseUrl }}" class="btn btn-secondary">Сбросить</a>
            </div>
        </form>
    </div>

    <div class="staff-grades-list">
        @if($grades->count() > 0)
            @foreach($grades as $grade)
                <article class="staff-grade-card">
                    <div>
                        <span class="page-label">
                            {{ $grade->assignment->class }} класс
                        </span>

                        <h4>{{ $grade->user->full_name }}</h4>

                        <p>
                            <strong>Предмет:</strong> {{ $grade->assignment->subject }}<br>
                            <strong>День:</strong> {{ $grade->assignment->day }}<br>
                            <strong>Неделя:</strong>
                            {{ \Carbon\Carbon::parse($grade->assignment->week_start_date)->format('d.m.Y') }}
                            —
                            {{ \Carbon\Carbon::parse($grade->assignment->week_start_date)->addDays(4)->format('d.m.Y') }}
                        </p>
                    </div>

                    <div class="staff-grade-value grade-{{ $grade->grade }}">
                        {{ $grade->grade }}
                    </div>
                </article>
            @endforeach
        @else
            <div class="diary-empty">
                <div class="diary-empty-icon">📊</div>
                <h4>Оценок пока нет</h4>
                <p>После выставления оценок они появятся на этой странице.</p>
            </div>
        @endif
    </div>

</section>

@endsection