@extends('layout')

@section('content')

<h2>Редактировать урок</h2>

<form method="POST" action="/admin/schedule/{{ $schedule->id }}/update">
@csrf

<input name="class" value="{{ $schedule->class }}" class="form-control mb-2">

<input name="day" value="{{ $schedule->day }}" class="form-control mb-2">

<input name="lesson_number" value="{{ $schedule->lesson_number }}" class="form-control mb-2">

<input name="subject" value="{{ $schedule->subject }}" class="form-control mb-2">

<input type="date" name="week_start_date" value="{{ $schedule->week_start_date }}" class="form-control mb-2">

<button class="btn btn-primary">Обновить</button>

</form>

@endsection