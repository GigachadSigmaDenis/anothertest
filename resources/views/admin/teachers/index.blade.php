@extends('layout')

@section('content')

<div class="card p-4">
    <h2 class="text-center mb-4">Управление учителями</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="text-end mb-3">
        <a href="/admin/teachers/create" class="btn btn-success-admin">+ Добавить учителя</a>
    </div>

    <div class="row">
        @forelse($teachers as $t)
        <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
            <div class="card teacher-admin-card h-100">
                @if($t->photo)
                    <img src="{{ asset('storage/'.$t->photo) }}" class="card-img-top teacher-image" alt="{{ $t->full_name }}">
                @else
                    <div class="card-img-top teacher-image-placeholder">
                        <span>Нет фото</span>
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-center">{{ $t->full_name }}</h5>
                    <p class="card-text text-center small text-muted flex-grow-1">
                        {{ Str::limit($t->subjects, 80) }}
                    </p>
                    <div class="btn-group mt-3">
                        <a href="/admin/teachers/edit/{{ $t->id }}" class="btn btn-warning-admin btn-sm">Редактировать</a>
                        <form method="POST" action="/admin/teachers/delete/{{ $t->id }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger-admin btn-sm" onclick="return confirm('Удалить учителя?')">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                Учителя не найдены
            </div>
        </div>
        @endforelse
    </div>
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
    
    .teacher-admin-card {
        border: 1px solid #e0e0e0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        border-radius: 12px;
    }
    
    .teacher-admin-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        border-color: #4e73df;
    }
    
    .teacher-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .teacher-image-placeholder {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
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