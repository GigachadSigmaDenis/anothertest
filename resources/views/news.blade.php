@extends('layout')

@section('content')

<style>
    .news-title {
        font-size: 18px;
        font-weight: 600;
        color: #111;
    }

    .news-text {
        font-size: 15px;
        color: #333;
        line-height: 1.5;
    }

    .news-date {
        font-size: 12px;
        color: #666;
    }

    .news-card {
        border: 1px solid #dcdcdc;
        background: #fafafa;
        transition: 0.2s ease;
    }

    .news-card:hover {
        background: #f3f6f9;
    }

    /* мягкие “дымные” метки */
    .badge-soft {
        font-size: 12px;
        padding: 5px 10px;
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

<h4 class="mb-4">Новости</h4>

@foreach($news as $item)

<div class="card news-card mb-3">
    <div class="row g-0">

        {{-- ТЕКСТ --}}
        <div class="col-md-8">
            <div class="card-body">

                <div class="news-title mb-1">
                    {{ $item->title }}
                </div>

                <div class="news-date mb-2">
                    {{ $item->published_at }}
                </div>

                <p class="news-text">
                    {{ Str::limit($item->content, 180) }}
                </p>

                {{-- МЕТКА --}}
                <span class="badge-soft
                    @if($item->category == 'безопасность') badge-danger-soft
                    @elseif($item->category == 'профориентация') badge-warning-soft
                    @else badge-success-soft
                    @endif">
                    {{ $item->category }}
                </span>

                <br><br>

                <a href="/news/{{ $item->id }}" class="btn btn-outline-dark btn-sm">
                    Подробнее
                </a>

            </div>
        </div>

        {{-- КАРТИНКА --}}
        <div class="col-md-4 d-flex align-items-center p-2">

            @if($item->image)
                <img 
                    src="{{ asset('storage/' . $item->image) }}" 
                    style="width:100%; height:160px; object-fit:cover; border:1px solid #ddd;"
                >
            @endif

        </div>

    </div>
</div>

@endforeach

@endsection