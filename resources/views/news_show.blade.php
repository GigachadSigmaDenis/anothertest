@extends('layout')

@section('content')

<style>
    .news-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 10px;
        word-wrap: break-word;
        overflow-wrap: break-word;
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
        word-wrap: break-word;
        overflow-wrap: break-word;
        white-space: normal;
    }

    .news-content p {
        word-wrap: break-word;
        overflow-wrap: break-word;
        white-space: normal;
        margin-bottom: 15px;
    }

    .news-image {
        width: 100%;
        height: auto;
        object-fit: contain;
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
        display: inline-block;
        word-wrap: break-word;
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
    
    .news-content img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 10px 0;
    }
    
    .news-content ul, .news-content ol {
        padding-left: 20px;
        margin-bottom: 15px;
    }
    
    .news-content li {
        margin-bottom: 5px;
    }
    
    .news-content h1, .news-content h2, .news-content h3 {
        margin-top: 20px;
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .image-container {
        text-align: center;
        margin-bottom: 20px;
    }
    
    .image-container img {
        max-width: 100%;
        height: auto;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 5px;
        background: #f9f9f9;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
</style>

<a href="/news" class="btn btn-outline-dark btn-sm mb-3">← Назад</a>

<div class="news-title">
    {{ $news->title }}
</div>

<div class="news-date">
    {{ \Carbon\Carbon::parse($news->published_at)->format('d.m.Y H:i') }}
</div>

@if($news->image)
<div class="image-container">
    <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}">
</div>
@endif

<div class="news-content">
    {!! nl2br(e($news->content)) !!}
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