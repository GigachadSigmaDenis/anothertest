<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Притобольная СОШ</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/Logo_Owl.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
            font-family: "Segoe UI", Arial, sans-serif;
            color: #2c3e50;
        }

        .header {
            background: linear-gradient(135deg, #4e73df, #224abe);
            color: white;
            padding: 20px 25px;
            border-bottom: 4px solid #1c3faa;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .header-logo {
            height: 70px;
            width: auto;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .header-text {
            text-align: center;
        }

        .header-text h5 {
            font-size: 14px;
            margin: 0;
        }

        .header-text h4 {
            font-size: 26px;
            margin-top: 5px;
            font-weight: bold;
        }

        .navbar {
            background: white !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: #224abe !important;
        }

        .nav-link {
            color: #333 !important;
            margin-right: 10px;
        }

        .nav-link:hover {
            color: #224abe !important;
        }

        .container {
            max-width: 1100px;
        }

        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .btn {
            border-radius: 8px;
        }

        .btn-primary {
            background: #4e73df;
            border: none;
        }

        .btn-primary:hover {
            background: #2e59d9;
        }

        .profile-card {
            text-align: center;
            padding: 20px;
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 4px solid #4e73df;
        }

        .footer {
            text-align: center;
            padding: 15px;
            margin-top: 40px;
            background: #fff;
            border-top: 1px solid #ddd;
            font-size: 14px;
        }
        
        .admin-link {
            color: #dc3545 !important;
            font-weight: bold;
        }
        
        .admin-link:hover {
            color: #bb2d3b !important;
        }
    </style>
</head>

<body>

<div class="header">
    <div class="header-container">
        <img src="{{ asset('storage/Logo_Owl.png') }}" alt="Логотип школы" class="header-logo">
        <div class="header-text">
            <h5>Муниципальное бюджетное общеобразовательное учреждение</h5>
            <h4>«Притобольная СОШ»</h4>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="/">Главная</a>

        <div class="navbar-nav">
            <a class="nav-link" href="/news">Новости</a>
            <a class="nav-link" href="/about">О школе</a>
            <a class="nav-link" href="/teachers">Учителя</a>
            <a class="nav-link" href="/schedule">Расписание</a>
            
            @auth
                @if(auth()->user()->role === 'admin')
                    <a class="nav-link admin-link" href="/admin/dashboard">Админка</a>
                @endif
                
                <a class="nav-link" href="/profile">Профиль</a>

                @if(auth()->user()->role === 'teacher')
                    <a class="nav-link" href="/teacher/classes">Классы</a>
                    <a class="nav-link" href="/teacher/schedule">Редактор расписания</a>
                @endif

                <a class="nav-link" href="/logout">Выйти</a>
            @else
                <a class="nav-link" href="/login">Вход</a>
                <a class="nav-link" href="/register">Регистрация</a>
            @endauth
        </div>
    </div>
</nav>

<div class="container mt-4">

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @yield('content')
</div>

<div class="footer">
    <div class="container">
        <h5>Контакты</h5>
        <p>
            Муниципальное бюджетное общеобразовательное учреждение<br>
            "Притобольная средняя общеобразовательная школа"
        </p>

        <p><strong>Адрес:</strong><br>
        Курганская область, Притобольный район,<br> с. Боровлянка, ул. Центральная, 6
        </p>

        <p><strong>Телефон:</strong><br>
        8 (35239) 9-37-05
        </p>

        <p><strong>Email:</strong><br>
        pritschool@mail.ru
        </p>

        <p><strong>Режим работы:</strong><br>
        Пн–пт 08:00–16:00
        </p>

        <hr>
        <p>© 2026 Притобольная СОШ</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>