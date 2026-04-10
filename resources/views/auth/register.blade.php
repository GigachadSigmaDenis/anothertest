@extends('layout')

@section('content')

<div class="card p-4">
    <h3>Вход</h3>

    <form method="POST" action="/register">
        @csrf

        <input class="form-control mb-2" name="login" placeholder="Логин">
        <input class="form-control mb-2" name="full_name" placeholder="ФИО">
        <input class="form-control mb-2" name="email" placeholder="Email">
        <input type="password" class="form-control mb-2" name="password" placeholder="Пароль">

        <button class="btn btn-primary">Зарегистрироваться</button>
    </form>
</div>

@endsection