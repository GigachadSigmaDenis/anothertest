@extends('layout')

@section('content')

<section class="admin-dashboard">

    <div class="admin-hero mb-4">
        <div class="admin-hero-content">
            <span class="page-label">Панель управления</span>

            <h2>Админ-панель</h2>

            <p>
                Управление основными разделами сайта школы: новостями, педагогами,
                расписанием, документами и пользователями.
            </p>
        </div>

        <div class="admin-user-card">
            <div class="admin-user-avatar">
                {{ mb_substr(auth()->user()->full_name ?? 'А', 0, 1) }}
            </div>

            <div>
                <span>Администратор</span>
                <strong>{{ auth()->user()->full_name }}</strong>
            </div>

            <a href="/logout" class="btn btn-secondary btn-sm">
                Выйти
            </a>
        </div>
    </div>

    <div class="admin-section mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Разделы</span>
                <h3>Управление сайтом</h3>
            </div>
        </div>

        <div class="admin-grid">
            <a href="/admin/news" class="admin-card-new">
                <div class="admin-card-icon">📰</div>

                <div class="admin-card-content">
                    <h4>Новости</h4>
                    <p>Добавление, редактирование и публикация новостей школы</p>
                </div>

                <span class="admin-card-arrow">→</span>
            </a>

            <a href="/admin/teachers" class="admin-card-new">
                <div class="admin-card-icon">👩‍🏫</div>

                <div class="admin-card-content">
                    <h4>Учителя</h4>
                    <p>Управление педагогическим составом и фотографиями</p>
                </div>

                <span class="admin-card-arrow">→</span>
            </a>

            <a href="/admin/schedule" class="admin-card-new">
                <div class="admin-card-icon">📅</div>

                <div class="admin-card-content">
                    <h4>Расписание</h4>
                    <p>Редактирование расписания уроков по классам и неделям</p>
                </div>

                <span class="admin-card-arrow">→</span>
            </a>

            <a href="/admin/documents" class="admin-card-new">
                <div class="admin-card-icon">📄</div>

                <div class="admin-card-content">
                    <h4>Документы</h4>
                    <p>Публикация официальных документов и ссылок</p>
                </div>

                <span class="admin-card-arrow">→</span>
            </a>

            <a href="/admin/users" class="admin-card-new">
                <div class="admin-card-icon">👤</div>

                <div class="admin-card-content">
                    <h4>Пользователи</h4>
                    <p>Просмотр и управление пользователями сайта</p>
                </div>

                <span class="admin-card-arrow">→</span>
            </a>

            <a href="/admin/diary" class="admin-card-new">
                <div class="admin-card-icon">📘</div>

                <div class="admin-card-content">
                    <h4>Электронный дневник</h4>
                    <p>Задания, материалы, ссылки, файлы и оценки учеников</p>
                </div>

                <span class="admin-card-arrow">→</span>
            </a>

            <a href="/admin/announcements" class="admin-card-new">
                <div class="admin-card-icon">📢</div>

                <div class="admin-card-content">
                    <h4>Объявления</h4>
                    <p>Создание мероприятий и информационных объявлений</p>
                </div>

                <span class="admin-card-arrow">→</span>
            </a>
        </div>
    </div>

</section>

@endsection