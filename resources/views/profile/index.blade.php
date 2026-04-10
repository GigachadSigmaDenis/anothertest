@extends('layout')

@section('content')

<div class="card profile-card">
    <div class="card-body text-center">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=4e73df&color=fff&size=120" class="avatar">
        
        <h3>{{ $user->full_name }}</h3>
        <p class="text-muted"><strong>Логин:</strong> {{ $user->login }}</p>
        <p class="text-muted"><strong>Email:</strong> {{ $user->email }}</p>
        
        <p>
            <strong>Роль:</strong> 
            @if($user->role == 'admin')
                <span class="badge bg-danger">Администратор</span>
            @elseif($user->role == 'teacher')
                <span class="badge bg-primary">Учитель</span>
            @elseif($user->role == 'student')
                <span class="badge bg-success">Ученик</span>
            @else
                <span class="badge bg-secondary">Гость</span>
            @endif
        </p>

        @if($user->role === 'student')
            <p><strong>Класс:</strong> {{ $user->studend_class ?? 'Не указан' }}</p>

            <h5 class="mt-4">Расписание на завтра</h5>

            @if($schedule && count($schedule) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="20%">Урок</th>
                                <th>Предмет</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedule as $lesson)
                                <tr>
                                    <td><strong>{{ $lesson->lesson_number }}</strong></td>
                                    <td>{{ $lesson->subject }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center">
                    На завтра уроков нет
                </div>
            @endif
        @endif

        @if($user->role === 'teacher')
            <div class="mt-4">
                <h5>Управление</h5>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <a href="/teacher/classes" class="btn btn-primary">Редактор классов</a>
                    <a href="/teacher/schedule" class="btn btn-primary">Редактор расписания</a>
                </div>
            </div>
        @endif

        @if($user->role === 'admin')
            <div class="mt-4">
                <h5>Управление</h5>
                <div class="d-flex justify-content-center mt-3">
                    <a href="/admin/dashboard" class="btn btn-danger">Админ-панель</a>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin-bottom: 15px;
        border: 4px solid #4e73df;
        object-fit: cover;
    }
    
    .profile-card {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .gap-2 {
        gap: 10px;
    }
    
    .btn {
        min-width: 180px;
    }
</style>

@endsection