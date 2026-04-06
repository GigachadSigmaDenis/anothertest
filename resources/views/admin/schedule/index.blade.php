@extends('layout')

@section('content')

<h2>Расписание (админ)</h2>

<form method="GET" class="mb-3">
    <input type="date" name="week_start"
           value="{{ $weekStart->format('Y-m-d') }}">

    <button class="btn btn-sm btn-primary">Выбрать неделю</button>
</form>

<div class="mb-3">
    <strong>Классы:</strong>

    <a href="/admin/schedule?week_start={{ $weekStart->format('Y-m-d') }}"
       class="btn btn-sm {{ !$class ? 'btn-success' : 'btn-primary' }}">
        Все
    </a>

    @foreach($classes as $cls)
        <a href="/admin/schedule?week_start={{ $weekStart->format('Y-m-d') }}&class={{ $cls }}"
           class="btn btn-sm {{ $class == $cls ? 'btn-success' : 'btn-primary' }}">
            {{ $cls }}
        </a>
    @endforeach
</div>

<div class="mb-3">
    <strong>Дни:</strong>

    <a href="/admin/schedule?week_start={{ $weekStart->format('Y-m-d') }}&class={{ $class }}"
       class="btn btn-sm {{ !$day ? 'btn-success' : 'btn-primary' }}">
        Все
    </a>

    @foreach($days as $d)
        <a href="/admin/schedule?week_start={{ $weekStart->format('Y-m-d') }}&class={{ $class }}&day={{ $d }}"
           class="btn btn-sm {{ $day == $d ? 'btn-success' : 'btn-primary' }}">
            {{ $d }}
        </a>
    @endforeach
</div>

<a href="/admin/schedule/create" class="btn btn-success mb-3">+ Добавить</a>

<table class="table table-bordered">

<tr>
    <th>Класс</th>
    <th>День</th>
    <th>Урок</th>
    <th>Предмет</th>
    <th>Неделя</th>
    <th>Действия</th>
</tr>

@forelse($schedules as $s)
<tr>
    <td>{{ $s->class }}</td>
    <td>{{ $s->day }}</td>
    <td>{{ $s->lesson_number }}</td>
    <td>{{ $s->subject }}</td>
    <td>{{ $s->week_start_date }}</td>
    <td>
        <a href="/admin/schedule/{{ $s->id }}/edit" class="btn btn-primary btn-sm">Редактировать</a>

        <form method="POST" action="/admin/schedule/{{ $s->id }}" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm">Удалить</button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center text-danger">
        Нет данных по выбранным фильтрам
    </td>
</tr>
@endforelse

</table>

@endsection