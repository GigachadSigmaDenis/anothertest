@extends('layout')

@section('content')

<div class="card p-4">
    <h3 class="text-center mb-4">Редактор классов учеников</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-10">
                <input name="search" 
                       class="form-control" 
                       placeholder="Поиск по логину или ФИО"
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Поиск</button>
            </div>
        </div>
    </form>

    @forelse($users as $user)
    <div class="card mb-3 user-list-card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <strong>{{ $user->login }}</strong>
                </div>
                <div class="col-md-3">
                    {{ $user->full_name }}
                </div>
                <div class="col-md-2">
                    @if($user->role == 'student')
                        <span class="badge bg-success">Ученик</span>
                    @elseif($user->role == 'guest')
                        <span class="badge bg-secondary">Гость</span>
                    @else
                        <span class="badge bg-warning">{{ $user->role }}</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <form method="POST" action="/teacher/classes/update" class="d-flex gap-1">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <select name="class" class="form-select form-select-sm flex-grow-1">
                            <option value="none" {{ $user->studend_class == 'none' ? 'selected' : '' }}>Без класса</option>
                            @for($i=1; $i<=9; $i++)
                                <option value="{{ $i }}" {{ $user->studend_class == $i ? 'selected' : '' }}>
                                    {{ $i }} класс
                                </option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Сохранить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info text-center">
        Пользователи не найдены
    </div>
    @endforelse
</div>

<style>
    .user-list-card {
        border: 1px solid #e0e0e0;
        transition: box-shadow 0.3s ease;
    }
    
    .user-list-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-color: #4e73df;
    }
    
    .badge {
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 20px;
    }
    
    .form-select-sm {
        font-size: 12px;
        padding: 4px 8px;
    }
    
    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }
    
    .d-flex {
        display: flex;
    }
    
    .gap-1 {
        gap: 5px;
    }
    
    .flex-grow-1 {
        flex-grow: 1;
    }
</style>

@endsection