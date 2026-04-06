@extends('layout')

@section('content')

<h2>Добавить урок</h2>

<form method="POST" action="/admin/schedule/store">
@csrf

<input name="class" class="form-control mb-2" placeholder="Класс">

<input name="day" class="form-control mb-2" placeholder="День (Понедельник)">

<input name="lesson_number" class="form-control mb-2" placeholder="Номер урока">

<input name="subject" class="form-control mb-2" placeholder="Предмет">

<input type="date" name="week_start_date" class="form-control mb-2">

<button class="btn btn-success">Сохранить</button>

</form>

@endsection