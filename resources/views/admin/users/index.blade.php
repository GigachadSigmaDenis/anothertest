@extends('layout')

@section('content')

<div class="card p-4">
    <h3 class="text-center mb-4">Редактор пользователей</h3>

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
                       placeholder="Поиск по логину, ФИО или email"
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
                <div class="col-md-2">
                    <strong>{{ $user->login }}</strong>
                </div>
                <div class="col-md-3">
                    {{ $user->full_name }}
                </div>
                <div class="col-md-2">
                    {{ $user->email }}
                </div>
                <div class="col-md-2">
                    @if($user->role === 'admin')
                        <span class="badge bg-danger">Админ</span>
                    @elseif($user->role === 'teacher')
                        <span class="badge bg-primary">Учитель</span>
                    @elseif($user->role === 'student')
                        <span class="badge bg-success">Ученик</span>
                    @else
                        <span class="badge bg-secondary">Гость</span>
                    @endif
                </div>
                <div class="col-md-3">
                    @if($user->role !== 'admin')
                    <div class="d-flex gap-1">
                        <form method="POST" action="/admin/users/update-role" class="flex-grow-1">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="guest" {{ $user->role == 'guest' ? 'selected' : '' }}>Гость</option>
                                <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Ученик</option>
                                <option value="teacher" {{ $user->role == 'teacher' ? 'selected' : '' }}>Учитель</option>
                            </select>
                        </form>
                        
                        @if($user->role !== 'teacher')
                        <form method="POST" action="/admin/users/update-class" class="flex-grow-1">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <div class="d-flex gap-1">
                                <select name="class" class="form-select form-select-sm">
                                    <option value="none" {{ is_null($user->studend_class) ? 'selected' : '' }}>Без класса</option>
                                    @for($i=1; $i<=11; $i++)
                                        <option value="{{ $i }}" {{ $user->studend_class == $i ? 'selected' : '' }}>
                                            {{ $i }} класс
                                        </option>
                                    @endfor
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">OK</button>
                            </div>
                        </form>
                        @endif
                        
                        <form method="POST" action="/admin/users/delete/{{ $user->id }}" onsubmit="return confirm('Удалить пользователя {{ $user->full_name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info text-center">
        Пользователи не найдены
    </div>
    @endforelse

    @if(method_exists($users, 'links'))
        <div class="d-flex justify-content-center mt-3">
            {{ $users->links() }}
        </div>
    @endif
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