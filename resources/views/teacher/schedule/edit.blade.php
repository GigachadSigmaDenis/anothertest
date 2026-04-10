@extends('layout')

@section('content')

<div class="card p-4">

    <h3 class="mb-4 text-center">Редактировать расписание</h3>
    <h5 class="mb-4 text-center text-muted">{{ $day }}, {{ $class }} класс</h5>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/teacher/schedule/update">
        @csrf

        <input type="hidden" name="class" value="{{ $class }}">
        <input type="hidden" name="day" value="{{ $day }}">
        <input type="hidden" name="week_start_date" value="{{ \Carbon\Carbon::parse($weekStart)->format('Y-m-d') }}">

        <div class="alert alert-info">
            <strong>Неделя:</strong> {{ \Carbon\Carbon::parse($weekStart)->format('d.m.Y') }} - 
            {{ \Carbon\Carbon::parse($weekStart)->addDays(4)->format('d.m.Y') }}
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="10%">Урок</th>
                        <th>Предмет</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i=1; $i<=7; $i++)
                        <tr>
                            <td><strong>{{ $i }}</strong></td>
                            <td>
                                <input name="lessons[{{ $i }}]"
                                       value="{{ $data[$i] ?? '' }}"
                                       class="form-control"
                                       placeholder="Название предмета (оставьте пустым, если нет урока)">
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg">Обновить расписание</button>
            <a href="/teacher/schedule?class={{ $class }}&week_start={{ $weekStart }}" class="btn btn-secondary btn-lg ms-2">Отмена</a>
        </div>

    </form>

</div>

@endsection