@extends('layout')

@section('content')

<section class="announcement-show-page">

    <div class="announcement-show-card">
        <div class="section-head">
            <div>
                <span class="page-label">Объявления</span>
                <h3>{{ $announcement->title }}</h3>
            </div>

            <a href="/announcements" class="section-link">
                ← Назад
            </a>
        </div>

        <div class="announcement-show-meta">
            <span class="announcement-type {{ $announcement->type === 'event' ? 'type-event' : 'type-info' }}">
                {{ $announcement->type === 'event' ? 'Мероприятие' : 'Информирование' }}
            </span>

        </div>

        <div class="announcement-show-dates">
            <p>
                <strong>Дата публикации:</strong>
                {{ optional($announcement->published_at)->format('d.m.Y H:i') ?? 'Не указана' }}
            </p>

            @if($announcement->event_at)
                <p>
                    <strong>Дата проведения:</strong>
                    {{ $announcement->event_at->format('d.m.Y H:i') }}
                </p>
            @endif
        </div>

        @if($announcement->image)
            <div class="announcement-show-image-wrap">
                <img src="{{ asset('storage/' . $announcement->image) }}"
                     class="announcement-show-image"
                     alt="{{ $announcement->title }}">
            </div>
        @endif

        <div class="announcement-show-content">
            @if($announcement->description)
                {!! nl2br(e($announcement->description)) !!}
            @else
                <p class="text-muted mb-0">
                    Описание не добавлено.
                </p>
            @endif
        </div>
    </div>

</section>

<style>
.section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 22px;
}

.section-link {
    flex-shrink: 0;
}
</style>

@endsection