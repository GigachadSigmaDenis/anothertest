@extends('layout')

@section('content')

<section class="page-section news-list-section">
    <div class="section-head">
        <div>
            <span class="page-label">Новости</span>
            <h3>Новости школы</h3>
        </div>
    </div>

    @if($news->count() > 0)
        <div class="news-list">
            @foreach($news as $item)
                <article class="news-list-card">
                    <div class="row g-0 align-items-stretch">
                        <div class="col-lg-8">
                            <div class="news-list-content">
                                <span class="news-category
                                    @if($item->category == 'безопасность') category-safety
                                    @elseif($item->category == 'профориентация') category-career
                                    @else category-education
                                    @endif">
                                    {{ $item->category }}
                                </span>

                                <h4>{{ $item->title }}</h4>

                                <p class="news-date">
                                    <strong>Дата публикации:</strong>
                                    {{ \Carbon\Carbon::parse($item->published_at)->format('d.m.Y H:i') }}
                                </p>

                                <p class="news-list-text">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->content), 220) }}
                                </p>

                                <a href="/news/{{ $item->id }}" class="btn btn-primary btn-sm">
                                    Подробнее →
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}"
                                     class="news-list-image"
                                     alt="{{ $item->title }}">
                            @else
                                <div class="news-list-placeholder">
                                    <span>Нет фото</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center mb-0">
            Новостей пока нет
        </div>
    @endif
</section>

@endsection