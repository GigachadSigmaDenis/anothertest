@extends('layout')

@section('content')

<div class="card p-4">
    <h2 class="text-center mb-4">Управление документами</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="text-end mb-3">
        <a href="/admin/documents/create" class="btn btn-success-admin">+ Добавить документ</a>
    </div>

    <div class="row" id="sortable">
        @foreach($documents as $index => $item)
        <div class="col-md-6 col-lg-4 mb-4" data-id="{{ $item->id }}">
            <div class="card document-card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="drag-handle text-center mb-2" style="cursor: move;">
                        <span class="text-muted">⋮⋮</span>
                    </div>
                    
                    <div class="document-icon text-center mb-3">
                        <span class="icon">📄</span>
                    </div>
                    
                    <h5 class="card-title text-center">{{ Str::limit($item->title, 60) }}</h5>
                    
                    <p class="card-text text-center text-muted small mb-3">
                        <a href="{{ $item->link }}" target="_blank" class="text-truncate d-block">
                            {{ Str::limit($item->link, 40) }}
                        </a>
                    </p>
                    
                    <div class="text-center mb-3">
                        @if($item->is_published)
                            <span class="badge bg-success">Опубликовано</span>
                        @else
                            <span class="badge bg-secondary">Скрыто</span>
                        @endif
                    </div>
                    
                    <div class="btn-group mt-auto">
                        <a href="/admin/documents/edit/{{ $item->id }}" class="btn btn-warning-admin btn-sm">Редактировать</a>
                        <form method="POST" action="/admin/documents/delete/{{ $item->id }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger-admin btn-sm" onclick="return confirm('Удалить документ?')">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    const sortable = new Sortable(document.getElementById('sortable'), {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function() {
            const items = document.querySelectorAll('#sortable .col-md-6');
            const order = [];
            items.forEach((item) => {
                order.push(item.dataset.id);
            });
            
            fetch('/admin/documents/update-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order: order })
            });
        }
    });
</script>

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
    
    .document-card {
        border: 1px solid #e0e0e0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        border-radius: 12px;
        cursor: pointer;
    }
    
    .document-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        border-color: #4e73df;
    }
    
    .document-icon .icon {
        font-size: 48px;
    }
    
    .card-title {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .badge {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
    }
    
    .btn-group {
        display: flex;
        gap: 8px;
    }
    
    .btn-group .btn {
        flex: 1;
    }
    
    .drag-handle {
        font-size: 20px;
        color: #999;
        cursor: move;
    }
    
    .drag-handle:hover {
        color: #4e73df;
    }
</style>

@endsection