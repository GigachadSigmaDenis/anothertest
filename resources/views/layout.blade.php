@php
    $layoutHasSoonEvent = false;

    $audiences = ['all'];

    if (auth()->check()) {
        $role = auth()->user()->role;

        if ($role === 'student') {
            $audiences = ['all', 'students'];
        } elseif (in_array($role, ['teacher', 'zam_dir', 'admin'])) {
            $audiences = ['all', 'students', 'teachers'];
        }
    }

    $layoutHasSoonEvent = \App\Models\Announcement::where('is_published', true)
        ->where('type', 'event')
        ->whereIn('audience', $audiences)
        ->whereNotNull('event_at')
        ->where('event_at', '>=', now())
        ->where('event_at', '<=', now()->addDay())
        ->where(function ($query) {
            $query->whereNull('published_at')
                ->orWhere('published_at', '<=', now());
        })
        ->when(auth()->check(), function ($query) {
            $query->whereDoesntHave('reads', function ($readQuery) {
                $readQuery->where('user_id', auth()->id());
            });
        })
        ->exists();
@endphp
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Притобольная СОШ</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/Logo_Owl.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body>

<header class="top-header">
    <div class="container">
        <div class="header-container">
            <img src="{{ asset('storage/Logo_Owl.png') }}" alt="Логотип школы" class="header-logo">

            <div>
                <div class="header-subtitle">
                    Муниципальное бюджетное общеобразовательное учреждение
                </div>
                <h1 class="header-title">
                    «Притобольная СОШ»
                </h1>
            </div>
        </div>
    </div>
</header>

<nav class="navbar navbar-expand-lg main-navbar">
    <div class="container">
        <a class="navbar-brand" href="/">Главная</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-nav ms-auto">
    <a class="nav-link" href="/news">Новости</a>
    <a class="nav-link announcements-nav-link {{ $layoutHasSoonEvent ? 'announcements-nav-link-active' : '' }}"
    href="/announcements">
        Объявления

        @if($layoutHasSoonEvent)
            <span class="announcements-nav-dot"></span>
        @endif
    </a>
    <a class="nav-link" href="/about">О школе</a>
    <a class="nav-link" href="/teachers">Учителя</a>
    <a class="nav-link" href="/schedule">Расписание</a>

    @auth
        @php
            $currentRole = auth()->user()->role;
        @endphp

        <a class="nav-link" href="/profile">Профиль</a>

        <div class="nav-control-wrapper">
            <button type="button"
                    class="nav-link nav-control-btn"
                    onclick="this.parentElement.classList.toggle('active')">
                Панель управления →
            </button>

            <div class="nav-control-panel">
                @if($currentRole === 'admin')
                    <a href="/admin/dashboard">Админ-панель</a>

                @elseif($currentRole === 'zam_dir')
                    <a href="/zam/classes">Редактор классов</a>
                    <a href="/zam/schedule">Редактор расписания</a>
                    <a href="/zam/diary">Электронный дневник</a>
                    <a href="/zam/grades">Все оценки</a>
                    <a href="/zam/announcements">Объявления</a>

                @elseif($currentRole === 'teacher')
                    <a href="/teacher/diary">Электронный дневник</a>
                    <a href="/teacher/grades">Все оценки</a>

                @elseif($currentRole === 'student')
                    <a href="/diary">Электронный дневник</a>

                @else
                    <span>Нет доступных разделов</span>
                @endif
            </div>
        </div>

        <a class="nav-link" href="/logout">Выйти</a>
    @else
        <a class="nav-link" href="/login">Вход</a>
        <a class="nav-link" href="/register">Регистрация</a>
    @endauth
</div>
    </div>
</nav>

<main class="container mt-4">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @yield('content')
</main>

<footer class="footer">
    <div class="container footer-inner">
        <div class="row g-4">
            <div class="col-lg-7">
                <h5 class="footer-title">Притобольная СОШ</h5>
                <p>
                    Муниципальное бюджетное общеобразовательное учреждение<br>
                    «Притобольная средняя общеобразовательная школа»
                </p>

                <p>
                    Официальный сайт образовательной организации.
                </p>
            </div>

            <div class="col-lg-5">
                <h5 class="footer-title">Контакты</h5>

                <p>
                    <strong>Адрес:</strong><br>
                    Курганская область, Притобольный район,<br>
                    с. Боровлянка, ул. Центральная, 6
                </p>

                <p>
                    <strong>Телефон:</strong><br>
                    8 (35239) 9-37-05
                </p>

                <p>
                    <strong>Email:</strong><br>
                    pritschool@mail.ru
                </p>

                <p>
                    <strong>Режим работы:</strong><br>
                    Пн–пт 08:00–16:00
                </p>
            </div>
        </div>

        <div class="footer-bottom text-center">
            © 2026 Притобольная СОШ
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>