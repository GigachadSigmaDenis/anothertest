@extends('layout')

@section('content')

<a href="/news" class="btn btn-secondary btn-sm mb-3">
    ← Назад к новостям
</a>

<article class="single-news-card">
    <div class="single-news-header">
        <span class="news-category
            @if($news->category == 'безопасность') category-safety
            @elseif($news->category == 'профориентация') category-career
            @else category-education
            @endif">
            {{ $news->category }}
        </span>

        <h2>{{ $news->title }}</h2>

        <p class="single-news-date">
            <strong>Дата публикации:</strong>
            {{ \Carbon\Carbon::parse($news->published_at)->format('d.m.Y') }}
        </p>
    </div>

    @if($news->image)
        <div class="single-news-image-wrap">
            <img src="{{ asset('storage/' . $news->image) }}"
                 class="single-news-image"
                 alt="{{ $news->title }}">
        </div>
    @endif

    <div class="single-news-content">
        {!! nl2br(e($news->content)) !!}
    </div>
</article>

@endsection