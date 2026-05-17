@extends('layout')

@section('content')

<section class="profile-page">

    <div class="profile-main-card">
        <div class="section-head">
            <div>
                <span class="page-label">Профиль</span>
                <h3>{{ $user->full_name }}</h3>
            </div>
        </div>

        <div class="profile-info-grid">
            <div class="profile-info-item">
                <span>Логин</span>
                <strong>{{ $user->login }}</strong>
            </div>

            <div class="profile-info-item">
                <span>Email</span>
                <strong>{{ $user->email }}</strong>
            </div>

            <div class="profile-info-item">
                <span>Роль</span>

                @if($user->role == 'admin')
                    <strong class="profile-role role-admin">Администратор</strong>
                @elseif($user->role == 'teacher')
                    <strong class="profile-role role-teacher">Учитель</strong>
                @elseif($user->role == 'student')
                    <strong class="profile-role role-student">Ученик</strong>
                @elseif($user->role == 'zam_dir')
                    <strong class="profile-role role-zam">Зам. директора</strong>
                @else
                    <strong class="profile-role role-guest">Гость</strong>
                @endif
            </div>

            @if($user->role === 'student')
                <div class="profile-info-item">
                    <span>Класс</span>
                    <strong>{{ $user->studend_class && $user->studend_class !== 'none' ? $user->studend_class . ' класс' : 'Не указан' }}</strong>
                </div>
            @endif
        </div>
    </div>

    @if($user->role === 'student')
        <div class="profile-section mt-4">
            <div class="section-head">
                <div>
                    <span class="page-label">Дневник</span>
                    <h3>Электронный дневник</h3>
                </div>
            </div>

            <div class="profile-actions profile-actions-single">
                <a href="/diary" class="profile-action-card">
                    <div class="profile-action-icon">📘</div>

                    <h4>Электронный дневник</h4>

                    <p>
                        Домашние задания, учебные материалы, прикреплённые файлы,
                        ссылки и оценки по предметам.
                    </p>
                </a>
            </div>
        </div>

        <div class="profile-section mt-4">
            <div class="section-head">
                <div>
                    <span class="page-label">Расписание</span>
                    <h3>Расписание на завтра</h3>
                </div>
            </div>

            @if($schedule && count($schedule) > 0)
                <div class="profile-schedule-list">
                    @foreach($schedule as $lesson)
                        <div class="profile-schedule-item">
                            <span>{{ $lesson->lesson_number }} урок</span>
                            <strong>{{ $lesson->subject }}</strong>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center mb-0">
                    На завтра уроков нет
                </div>
            @endif
        </div>
    @endif

    @if($user->role === 'teacher')
        <div class="profile-section mt-4">
            <div class="section-head">
                <div>
                    <span class="page-label">Управление</span>
                    <h3>Панель учителя</h3>
                </div>
            </div>

            <div class="profile-actions">
                <a href="/teacher/diary" class="profile-action-card">
                    <div class="profile-action-icon">📘</div>
                    <h4>Электронный дневник</h4>
                    <p>Задания, материалы, ссылки, файлы и оценки</p>
                </a>

                <a href="/teacher/grades" class="profile-action-card">
                    <div class="profile-action-icon">📊</div>
                    <h4>Все оценки</h4>
                    <p>Просмотр выставленных оценок учеников</p>
                </a>
            </div>
        </div>
    @endif

    @if($user->role === 'zam_dir')
        <div class="profile-section mt-4">
            <div class="section-head">
                <div>
                    <span class="page-label">Управление</span>
                    <h3>Панель заместителя директора</h3>
                </div>
            </div>

            <div class="profile-actions">
                <a href="/zam/classes" class="profile-action-card">
                    <div class="profile-action-icon">👥</div>
                    <h4>Редактор классов</h4>
                    <p>Назначение учеников по классам</p>
                </a>

                <a href="/zam/schedule" class="profile-action-card">
                    <div class="profile-action-icon">📅</div>
                    <h4>Редактор расписания</h4>
                    <p>Изменение расписания по классам и неделям</p>
                </a>

                <a href="/zam/diary" class="profile-action-card">
                    <div class="profile-action-icon">📘</div>
                    <h4>Электронный дневник</h4>
                    <p>Задания, материалы, ссылки, файлы и оценки</p>
                </a>

                <a href="/zam/grades" class="profile-action-card">
                    <div class="profile-action-icon">📊</div>
                    <h4>Все оценки</h4>
                    <p>Просмотр выставленных оценок учеников</p>
                </a>

                <a href="/zam/announcements" class="profile-action-card">
                    <div class="profile-action-icon">📢</div>
                    <h4>Объявления</h4>
                    <p>Создание и редактирование объявлений школы</p>
                </a>
            </div>
        </div>
    @endif

    @if($user->role === 'admin')
        <div class="profile-section mt-4">
            <div class="section-head">
                <div>
                    <span class="page-label">Управление</span>
                    <h3>Панель администратора</h3>
                </div>
            </div>

            <div class="profile-actions">
                <a href="/admin/dashboard" class="profile-action-card">
                    <div class="profile-action-icon">⚙️</div>
                    <h4>Админ-панель</h4>
                    <p>Управление сайтом и пользователями</p>
                </a>
            </div>
        </div>
    @endif

</section>

@endsection