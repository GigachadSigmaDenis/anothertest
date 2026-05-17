@extends('layout')

@section('content')

<section class="diary-page">

    <div class="diary-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Электронный дневник</span>
                <h3>Мои задания и оценки</h3>
            </div>

            <a href="/profile" class="section-link">← В профиль</a>
        </div>

        <p class="diary-hero-text">
            Выберите неделю и день, затем нажмите на предмет в расписании,
            чтобы посмотреть домашнее задание, материалы и оценку.
        </p>
    </div>

    @if(!$hasClass)
        <div class="diary-empty">
            <div class="diary-empty-icon">📘</div>
            <h4>Класс не указан</h4>
            <p>Администратор должен назначить вам класс в системе.</p>
        </div>
    @else
        <div class="diary-filter mb-4">
            <form method="GET" class="row g-3">
                <div class="col-lg-6">
                    <label class="form-label">Учебная неделя</label>
                    <input type="date"
                           name="week_start"
                           id="studentDiaryWeek"
                           class="form-control"
                           value="{{ $weekStart->format('Y-m-d') }}">
                </div>

                <div class="col-lg-4">
                    <label class="form-label">День недели</label>
                    <select name="day" class="form-select">
                        @foreach($days as $day)
                            <option value="{{ $day }}" {{ $selectedDay == $day ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Показать</button>
                </div>
            </form>
        </div>

        <div class="diary-schedule">
            <div class="section-head">
                <div>
                    <span class="page-label">{{ auth()->user()->studend_class }} класс</span>
                    <h3>{{ $selectedDay }}</h3>
                </div>

                <div class="diary-week-badge">
                    {{ $weekStart->format('d.m.Y') }} — {{ $weekStart->copy()->addDays(4)->format('d.m.Y') }}
                </div>
            </div>

            @if($lessons->count() > 0)
                <div class="diary-lessons-grid">
                    @foreach($diaryData as $lessonNumber => $item)
                        @php
                            $assignment = $item['assignment'];
                            $grade = $item['grade'];
                            $lesson = $item['lesson'];
                        @endphp

                        <button type="button"
                                class="diary-lesson-card"
                                onclick="openDiaryModal('diaryModal{{ $lessonNumber }}')">
                            <div class="diary-lesson-number">{{ $lessonNumber }} урок</div>

                            <h4>{{ $lesson->subject }}</h4>

                            <p>
                                @if($assignment)
                                    Задание добавлено
                                @else
                                    Задание пока не добавлено
                                @endif
                            </p>

                            @if($grade)
                                <span class="diary-grade-badge grade-{{ $grade }}">
                                    {{ $grade }}
                                </span>
                            @endif
                        </button>

                        <div id="diaryModal{{ $lessonNumber }}" class="diary-modal" style="display: none;">
                            <div class="diary-modal-box">
                                <button type="button" class="diary-modal-close" onclick="closeDiaryModal('diaryModal{{ $lessonNumber }}')">
                                    &times;
                                </button>

                                <div class="diary-modal-head">
                                    <div>
                                        <span class="page-label">{{ $lessonNumber }} урок</span>
                                        <h3>{{ $lesson->subject }}</h3>
                                    </div>

                                    <div class="diary-modal-grade">
                                        <span>Оценка</span>
                                        @if($grade)
                                            <strong class="grade-{{ $grade }}">{{ $grade }}</strong>
                                        @else
                                            <strong>—</strong>
                                        @endif
                                    </div>
                                </div>

                                <div class="diary-modal-content">
                                    <h4>Задание</h4>

                                    @if($assignment && $assignment->text)
                                        <p>{!! nl2br(e($assignment->text)) !!}</p>
                                    @else
                                        <p class="text-muted">Задание пока не добавлено.</p>
                                    @endif

                                    @if($assignment && $assignment->links->count() > 0)
                                        <h4>Ссылки</h4>

                                        <div class="diary-materials">
                                            @foreach($assignment->links as $link)
                                                <a href="{{ $link->url }}" target="_blank">
                                                    {{ $link->title ?: $link->url }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($assignment && $assignment->files->count() > 0)
                                        <h4>Файлы</h4>

                                        <div class="diary-materials">
                                            @foreach($assignment->files as $file)
                                                <a href="{{ $file->path }}" target="_blank">
                                                    {{ $file->original_name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="diary-empty">
                    <div class="diary-empty-icon">📅</div>
                    <h4>Расписание не найдено</h4>
                    <p>На выбранный день расписание ещё не добавлено.</p>
                </div>
            @endif
        </div>
    @endif

</section>

<script>
    function openDiaryModal(id) {
        const modal = document.getElementById(id);
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDiaryModal(id) {
        const modal = document.getElementById(id);
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.diary-modal').forEach(function (modal) {
                modal.style.display = 'none';
            });

            document.body.style.overflow = '';
        }
    });
</script>

@endsection