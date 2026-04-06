@extends('layout')

@section('content')

<h2>Вход в админку</h2>

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<form method="POST" action="/admin/login">
    @csrf

    <div class="mb-3">
        <input type="text" name="login" class="form-control" placeholder="Логин">
    </div>

    <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Пароль">
    </div>

    <button class="btn btn-primary">Войти</button>
</form>

@endsection