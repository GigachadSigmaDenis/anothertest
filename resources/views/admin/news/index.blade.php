@extends('layout')

@section('content')

<div class="card p-4">
    <h2 class="text-center mb-4">Управление новостями</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="text-end mb-3">
        <a href="/admin/news/create" class="btn btn-success-admin">+ Добавить новость</a>
    </div>

    <div class="row">
        @forelse($news as $item)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card news-admin-card h-100">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" 
                         class="card-img-top news-image"
                         alt="{{ $item->title }}">
                @else
                    <div class="card-img-top news-image-placeholder">
                        <span>Нет изображения</span>
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ Str::limit($item->title, 60) }}</h5>
                    
                    <div class="mb-2">
                        <span class="badge 
                            @if($item->category == 'безопасность') bg-danger
                            @elseif($item->category == 'профориентация') bg-warning
                            @else bg-success
                            @endif">
                            {{ $item->category }}
                        </span>
                    </div>
                    
                    <p class="card-text text-muted small mb-2">
                        <strong>Дата:</strong> {{ \Carbon\Carbon::parse($item->published_at)->format('d.m.Y H:i') }}
                    </p>
                    
                    <p class="card-text flex-grow-1">
                        {{ Str::limit(strip_tags($item->content), 100) }}
                    </p>
                    
                    <div class="btn-group mt-3">
                        <a href="/admin/news/edit/{{ $item->id }}" class="btn btn-warning-admin btn-sm">Редактировать</a>
                        <form method="POST" action="/admin/news/delete/{{ $item->id }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger-admin btn-sm" onclick="return confirm('Удалить новость?')">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                Новостей пока нет
            </div>
        </div>
        @endforelse
    </div>

    @if(method_exists($news, 'links'))
        <div class="d-flex justify-content-center mt-3">
            {{ $news->links() }}
        </div>
    @endif
</div>

<style>
    .btn-success-admin {
        background: #28a745;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }
    
    .btn-success-admin:hover {
        background: #218838;
        transform: translateY(-2px);
        color: white;
    }
    
    .btn-warning-admin {
        background: #ffc107;
        color: #212529;
        border: none;
        padding: 5px 12px;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-warning-admin:hover {
        background: #e0a800;
        color: #212529;
    }
    
    .btn-danger-admin {
        background: #dc3545;
        color: white;
        border: none;
        padding: 5px 12px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .btn-danger-admin:hover {
        background: #c82333;
        color: white;
    }
    
    .news-admin-card {
        border: 1px solid #e0e0e0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        border-radius: 12px;
    }
    
    .news-admin-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        border-color: #4e73df;
    }
    
    .news-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    
    .news-image-placeholder {
        width: 100%;
        height: 180px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
    }
    
    .badge {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
    }
    
    .card-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #2c3e50;
    }
    
    .btn-group {
        display: flex;
        gap: 8px;
    }
    
    .btn-group .btn {
        flex: 1;
    }
</style>

@endsection