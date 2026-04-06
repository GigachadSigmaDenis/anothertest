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
    }

    .admin-card .title {
        font-size: 16px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
</style>
@section('content')

<div class="container text-center mt-5">

    <h2 class="mb-4">Админ-панель</h2>

    <div class="row justify-content-center g-4">

        <div class="col-md-3">
            <a href="/admin/news" class="admin-card">
                <div class="icon">!</div>
                <div class="title">Новости</div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="/admin/teachers" class="admin-card">
                <div class="icon">🕮</div>
                <div class="title">Учителя</div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="/admin/schedule" class="admin-card">
                <div class="icon">✓</div>
                <div class="title">Расписание</div>
            </a>
        </div>

    </div>
</div>

@endsection