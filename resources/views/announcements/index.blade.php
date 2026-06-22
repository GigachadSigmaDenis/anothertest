@extends('layout')

@section('content')

<section class="announcements-page">

    <div class="announcements-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Объявления</span>
                <h3>Объявления школы</h3>
            </div>
        </div>

        <p class="announcements-hero-text">
            Здесь публикуются важные объявления, мероприятия и информационные сообщения школы.
        </p>
    </div>

    @php
        $readAnnouncementIds = auth()->check()
            ? \App\Models\AnnouncementRead::where('user_id', auth()->id())->pluck('announcement_id')->toArray()
            : [];

        $soonEvents = $announcements->filter(function ($item) use ($readAnnouncementIds) {
            return $item->type === 'event'
                && $item->event_at
                && $item->event_at >= now()
                && $item->event_at <= now()->addDay()
                && !in_array($item->id, $readAnnouncementIds);
        });
    @endphp

    @if($soonEvents->count() > 0)
        <div class="announcements-soon-box mb-4" id="announcementsSoonBox">
            <strong>Скоро мероприятие!</strong>
            В ближайшие 24 часа запланировано мероприятие. Непрочитанные мероприятия подсвечены ниже.
        </div>
    @endif

    @if($announcements->count() > 0)
        <div class="announcements-grid">
           @foreach($announcements as $announcement)
    @php
        $isSoonUnread = $announcement->type === 'event'
            && $announcement->event_at
            && $announcement->event_at >= now()
            && $announcement->event_at <= now()->addDay()
            && !in_array($announcement->id, $readAnnouncementIds);
    @endphp

    <a href="/announcements/{{ $announcement->id }}"
       class="announcement-card announcement-card-link {{ $isSoonUnread ? 'announcement-card-soon' : '' }}"
       id="announcementCard{{ $announcement->id }}">

        @if($announcement->image)
            <img src="{{ asset('storage/' . $announcement->image) }}"
                 class="announcement-image"
                 alt="{{ $announcement->title }}">
        @else
            <div class="announcement-image-placeholder">
                <span>Нет изображения</span>
            </div>
        @endif

        <div class="announcement-card-body">
            <div class="announcement-meta">
                <span class="announcement-type {{ $announcement->type === 'event' ? 'type-event' : 'type-info' }}">
                    {{ $announcement->type === 'event' ? 'Мероприятие' : 'Информирование' }}
                </span>

                @if($isSoonUnread)
                    <span class="announcement-soon-label">
                        Скоро
                    </span>
                @endif
            </div>

            <h4 class="announcement-title-clamp">
                {{ $announcement->title }}
            </h4>

            <div class="announcement-dates">
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

            @if($announcement->description)
                <p class="announcement-description announcement-description-clamp">
                    {{ strip_tags($announcement->description) }}
                </p>
            @endif

            <span class="announcement-more">
                Подробнее →
            </span>

            @auth
                @if($isSoonUnread)
                    <button type="button"
                            class="announcement-read-btn mt-3"
                            onclick="markAnnouncementCardRead(event, {{ $announcement->id }})">
                        Прочитано
                    </button>
                @endif
            @endauth
        </div>
    </a>
@endforeach
        </div>
    @else
        <div class="announcements-empty">
            <div class="announcements-empty-icon">📢</div>
            <h4>Объявлений пока нет</h4>
            <p>Здесь появятся важные сообщения школы.</p>
        </div>
    @endif

</section>

<script>
    function markAnnouncementCardRead(event, id) {
        event.preventDefault();
        event.stopPropagation();

        fetch('/announcements/read/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(function () {
            const card = document.getElementById('announcementCard' + id);

            if (card) {
                card.classList.remove('announcement-card-soon');

                const label = card.querySelector('.announcement-soon-label');
                const button = card.querySelector('.announcement-read-btn');

                if (label) label.remove();
                if (button) button.remove();
            }

            const unreadSoonCards = document.querySelectorAll('.announcement-card-soon');

            if (unreadSoonCards.length === 0) {
                const soonBox = document.getElementById('announcementsSoonBox');

                if (soonBox) {
                    soonBox.remove();
                }
            }
        });
    }
</script>

@endsection