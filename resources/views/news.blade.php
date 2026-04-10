@extends('layout')

@section('content')

<div class="card p-4">
    <h3 class="text-center mb-4">Новости школы</h3>

    @if($news->count() > 0)
        @foreach($news as $item)
        <div class="card mb-4 news-card">
            <div class="row g-0">
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->title }}</h5>
                        
                        <p class="text-muted small mb-2">
                            <strong>Дата публикации:</strong> {{ \Carbon\Carbon::parse($item->published_at)->format('d.m.Y H:i') }}
                        </p>

                        <p class="card-text">
                            {{ Str::limit(strip_tags($item->content), 200) }}
                        </p>

                        <span class="badge 
                            @if($item->category == 'безопасность') bg-danger
                            @elseif($item->category == 'профориентация') bg-warning
                            @else bg-success
                            @endif">
                            {{ $item->category }}
                        </span>

                        <div class="mt-3">
                            <a href="/news/{{ $item->id }}" class="btn btn-primary btn-sm">
                                Подробнее →
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" 
                             class="news-image"
                             alt="{{ $item->title }}">
                    @else
                        <div class="news-image-placeholder">
                            <span>Нет фото</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="alert alert-info text-center">
            Новостей пока нет
        </div>
    @endif
</div>

<style>
    .news-card {
        border: 1px solid #e0e0e0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    
    .news-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        border-color: #4e73df;
    }
    
    .news-image {
        width: 100%;
        height: 100%;
        min-height: 200px;
        object-fit: cover;
    }
    
    .news-image-placeholder {
        width: 100%;
        height: 100%;
        min-height: 200px;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
    }
    
    .badge {
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 20px;
    }
    
    .card-title {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    .btn-primary {
        background: #4e73df;
        border: none;
        border-radius: 8px;
    }
    
    .btn-primary:hover {
        background: #224abe;
    }
</style>

@endsection