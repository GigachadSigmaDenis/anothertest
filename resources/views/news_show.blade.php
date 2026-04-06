@extends('layout')

@section('content')

<style>
    .news-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .news-date {
        font-size: 13px;
        color: #666;
        margin-bottom: 15px;
    }

    .news-content {
        font-size: 17px;
        line-height: 1.7;
        color: #222;
    }

    .news-image {
        width: 100%;
        max-height: 400px;
        object-fit: cover;

        border: 1px solid #ddd;
        border-radius: 6px; 
        padding: 2px;       
        background: #fff;  
        margin-bottom: 15px;
    }

    .badge-soft {
        font-size: 13px;
        padding: 6px 10px;
        border-radius: 0;
        border: 1px solid #ccc;
        background: #eef2f6;
        color: #2c3e50;
    }

    .badge-danger-soft {
        background: #f3eaea;
        border: 1px solid #e0bcbc;
        color: #7a2d2d;
    }

    .badge-warning-soft {
        background: #f6f3ea;
        border: 1px solid #e0d6bc;
        color: #6b5a2d;
    }

    .badge-success-soft {
        background: #eaf3ee;
        border: 1px solid #bcdcc8;
        color: #2d6b45;
    }
</style>

<a href="/news" class="btn btn-outline-dark btn-sm mb-3">← Назад</a>

<div class="news-title">
    {{ $news->title }}
</div>

<div class="news-date">
    {{ $news->published_at }}
</div>

@if($news->image)
    <img src="{{ asset('storage/' . $news->image) }}" class="news-image">
@endif

<div class="news-content">
    {!! $news->content !!}
</div>

<hr>

<span class="badge-soft
    @if($news->category == 'безопасность') badge-danger-soft
    @elseif($news->category == 'профориентация') badge-warning-soft
    @else badge-success-soft
    @endif">
    {{ $news->category }}
</span>

@endsection