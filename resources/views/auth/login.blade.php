@extends('layout')

@section('content')

<div class="card p-4">
    <h3>Вход</h3>

    <form method="POST" action="/login">
        @csrf

        <input class="form-control mb-2" name="login" placeholder="Логин">
        <input type="password" class="form-control mb-2" name="password" placeholder="Пароль">

        <button class="btn btn-primary">Войти</button>
    </form>
</div>

@endsection