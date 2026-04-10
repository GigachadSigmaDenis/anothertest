@extends('layout')

@section('content')

<div class="card p-4">

    <h3 class="mb-4 text-center">Добавить расписание на день</h3>

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

    <form method="POST" action="/teacher/schedule/store">
        @csrf

        <div class="row justify-content-center mb-4">
            <div class="col-md-3">
                <label class="form-label">Класс *</label>
                <input name="class" class="form-control" placeholder="Например: 6" value="{{ old('class') }}" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">День недели *</label>
                <select name="day" class="form-control" required>
                    <option value="">Выберите день</option>
                    <option value="Понедельник" {{ old('day') == 'Понедельник' ? 'selected' : '' }}>Понедельник</option>
                    <option value="Вторник" {{ old('day') == 'Вторник' ? 'selected' : '' }}>Вторник</option>
                    <option value="Среда" {{ old('day') == 'Среда' ? 'selected' : '' }}>Среда</option>
                    <option value="Четверг" {{ old('day') == 'Четверг' ? 'selected' : '' }}>Четверг</option>
                    <option value="Пятница" {{ old('day') == 'Пятница' ? 'selected' : '' }}>Пятница</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Дата начала недели *</label>
                <input type="date" name="week_start_date" class="form-control" value="{{ old('week_start_date') }}" required>
                <small class="text-muted">Дата понедельника этой недели</small>
            </div>
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
                                       class="form-control" 
                                       placeholder="Название предмета (оставьте пустым, если нет урока)"
                                       value="{{ old('lessons.' . $i) }}">
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success btn-lg">Сохранить расписание</button>
            <a href="/teacher/schedule" class="btn btn-secondary btn-lg ms-2">Отмена</a>
        </div>

    </form>

</div>

@endsection