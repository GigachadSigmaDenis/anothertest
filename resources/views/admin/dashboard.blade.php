@extends('layout')

<style>
    .admin-card {
        display: block;
        border: 1px solid #000;
        padding: 25px 15px;
        text-decoration: none;
        color: #000;
        background: #fff;
        transition: all 0.2s ease;
        height: 140px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .admin-card:hover {
        background: #000;
        color: #fff;
    }

    .admin-card .icon {
        font-size: 26px;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .admin-card .title {
        font-size: 16px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
</style>

@section('content')

<div class="container text-center mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Админ-панель</h2>
        <div>
            <span class="badge bg-primary me-2">Администратор/Пользователь: {{ auth()->user()->full_name }}</span>
            <a href="/logout" class="btn btn-danger btn-sm">Выйти</a>
        </div>
    </div>

    <div class="row justify-content-center g-4">

        <div class="col-md-3">
            <a href="/admin/news" class="admin-card">
                <div class="icon">ⓘ</div>
                <div class="title">Новости</div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="/admin/teachers" class="admin-card">
                <div class="icon">✎</div>
                <div class="title">Учителя</div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="/admin/schedule" class="admin-card">
                <div class="icon">🗒</div>
                <div class="title">Расписание</div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="/admin/documents" class="admin-card">
                <div class="icon">🕮</div>
                <div class="title">Документы</div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="/admin/users" class="admin-card">
                <div class="icon">🔒︎</div>
                <div class="title">Пользователи</div>
            </a>
        </div>

    </div>
</div>

@endsection