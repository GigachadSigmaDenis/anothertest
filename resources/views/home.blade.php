@extends('layout')

@section('content')

<div class="school-home">

    <section class="home-hero mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="page-label mb-3">
                    Официальный сайт школы
                </div>

                <h2 class="home-title">
                    Муниципальное бюджетное общеобразовательное учреждение<br>
                    «Притобольная средняя общеобразовательная школа»
                </h2>

                <p class="home-text">
                    Добро пожаловать на официальный сайт Притобольной СОШ.
                    Здесь размещается актуальная информация о деятельности школы,
                    новостях, расписании, педагогах и важных событиях.
                </p>

                <div class="home-actions">
                    <a href="/news" class="btn btn-primary btn-lg">
                        Смотреть новости
                    </a>

                    <a href="https://www.gosuslugi.ru/600426/1/form"
                       target="_blank"
                       class="btn btn-outline-primary btn-lg">
                        Запись в 1 класс
                    </a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="home-image-box">
                    <img src="{{ asset('storage/school.jpg') }}"
                         class="home-image"
                         alt="Притобольная СОШ">
                </div>
            </div>
        </div>
    </section>

    <section class="quick-links mb-4">
        <div class="row g-3">
            <div class="col-md-3 col-sm-6">
                <a href="/about" class="quick-card">
                    <div class="quick-icon">🏫</div>
                    <h5>О школе</h5>
                    <p>Основные сведения</p>
                </a>
            </div>

            <div class="col-md-3 col-sm-6">
                <a href="/teachers" class="quick-card">
                    <div class="quick-icon">👩‍🏫</div>
                    <h5>Учителя</h5>
                    <p>Педагогический состав</p>
                </a>
            </div>

            <div class="col-md-3 col-sm-6">
                <a href="/schedule" class="quick-card">
                    <div class="quick-icon">📅</div>
                    <h5>Расписание</h5>
                    <p>Учебные занятия</p>
                </a>
            </div>

            <div class="col-md-3 col-sm-6">
                <a href="/news" class="quick-card">
                    <div class="quick-icon">📰</div>
                    <h5>Новости</h5>
                    <p>События школы</p>
                </a>
            </div>
        </div>
    </section>

    <section class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="info-card h-100">
                <h4>Краткая информация</h4>

                <div class="info-row">
                    <span>Год создания</span>
                    <strong>1985</strong>
                </div>

                <div class="info-row">
                    <span>Телефон</span>
                    <strong>8 (35239) 9-37-05</strong>
                </div>

                <div class="info-row">
                    <span>Email</span>
                    <strong>pritschool@mail.ru</strong>
                </div>

                <div class="info-row">
                    <span>Режим работы</span>
                    <strong>Пн–пт 08:00–16:00</strong>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="info-card h-100">
                <h4>Образовательная организация</h4>

                <p>
                    Притобольная СОШ — это школа, где созданы условия для обучения,
                    воспитания и развития учеников. На сайте можно найти новости,
                    расписание, информацию об учителях и полезные материалы для родителей.
                </p>

                <div class="blue-notice">
                    <strong>Информация для родителей:</strong>
                    заявление на зачисление ребёнка в первый класс можно подать
                    через портал Госуслуг.
                </div>
            </div>
        </div>
    </section>

    <section class="results-section mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Достижения</span>
                <h3>Результаты школы</h3>
            </div>
        </div>

        <div class="results-grid">
            <div class="result-item">
                <div class="result-number">5</div>
                <p>Призёров Всероссийской олимпиады за 2022–2023 уч. год</p>
            </div>

            <div class="result-item">
                <div class="result-number">38</div>
                <p>Оценка качества по ЕГЭ в 2022 году</p>
            </div>

            <div class="result-item">
                <div class="result-number">100%</div>
                <p>Выпускников, поступивших в СУЗы в 2022 году</p>
            </div>
        </div>
    </section>

    <section class="director-section mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Руководство</span>
                <h3>Руководство. Педагогический состав</h3>
            </div>
        </div>

        <div class="director-card">
            <div class="row g-4 align-items-center">
                <div class="col-lg-3 col-md-4">
                    <img src="{{ asset('storage/Director.png') }}"
                        class="director-photo"
                        alt="Директор школы">
                </div>

                <div class="col-lg-3 col-md-8">
                    <h4>Александров Александр Александрович</h4>
                    <p class="director-position">Директор школы</p>

                    <div class="director-info">
                        <strong>Телефон</strong>
                        <span>8 (35239) 9-37-05</span>
                    </div>

                    <div class="director-info">
                        <strong>Электронная почта</strong>
                        <span>pritschool@mail.ru</span>
                    </div>

                    <div class="director-info">
                        <strong>Часы приёма</strong>
                        <span>Понедельник – пятница: с 8.30 до 16.00</span>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="director-message">
                        <p>
                            Здравствуйте! Мы рады приветствовать Вас на официальном сайте
                            МБОУ «Притобольная СОШ». На нашем сайте Вы познакомитесь
                            с информацией о деятельности школы, жизни наших воспитанников
                            и педагогов.
                        </p>

                        <p>
                            Страницы сайта содержат полезную и интересующую Вас информацию.
                            Желаем приятного знакомства с нашей школой!
                        </p>

                        <p class="mb-0">
                            С уважением, директор школы Александров А.А.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="teachers-preview mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Педагоги</span>
                <h3>Педагоги школы</h3>
            </div>

            <a href="/teachers" class="section-link">
                Все учителя →
            </a>
        </div>

        <div class="row g-4">
            @forelse($teachers ?? [] as $teacher)
                <div class="col-lg-4 col-md-6">
                    <a href="/teachers" class="teacher-preview-card">
                        @if($teacher->photo)
                            <div class="teacher-preview-photo-wrap">
                                <img src="{{ asset('storage/' . $teacher->photo) }}"
                                    class="teacher-preview-photo"
                                    alt="{{ $teacher->full_name }}">
                            </div>
                        @else
                            <div class="teacher-preview-empty">
                                <span>Нет фото</span>
                            </div>
                        @endif

                        <div class="teacher-preview-body">
                            <h5>{{ $teacher->full_name }}</h5>

                            <p>
                                <strong>Предметы:</strong><br>
                                {{ \Illuminate\Support\Str::limit($teacher->subjects, 90) }}
                            </p>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center mb-0">
                        Информация о педагогах пока не добавлена
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <section class="latest-news mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Новости</span>
                <h3>Последняя новость</h3>
            </div>

            <a href="/news" class="section-link">
                Все новости →
            </a>
        </div>

        @if($news)
            <div class="news-box">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-8">
                        <div class="news-content">
                           <span class="news-category
                                @if($news->category == 'безопасность') category-safety
                                @elseif($news->category == 'профориентация') category-career
                                @else category-education
                                @endif">
                                {{ $news->category }}
                            </span>

                            <h4>{{ $news->title }}</h4>

                            <p class="news-date">
                                <strong>Дата публикации:</strong>
                                {{ \Carbon\Carbon::parse($news->published_at)->format('d.m.Y H:i') }}
                            </p>

                            <p>
                                {{ \Illuminate\Support\Str::limit(strip_tags($news->content), 240) }}
                            </p>

                            <a href="/news" class="btn btn-primary">
                                Читать подробнее
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        @if($news->image)
                            <img src="{{ asset('storage/' . $news->image) }}"
                                 class="news-image"
                                 alt="{{ $news->title }}">
                        @else
                            <div class="news-empty">
                                <span>Нет изображения</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info text-center mb-0">
                Новостей пока нет
            </div>
        @endif
    </section>

    <section class="gosuslugi-box">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <span class="page-label">Госуслуги</span>

                <h3>Запись в 1 класс</h3>

                <p>
                    Подать заявление на зачисление ребёнка в первый класс
                    можно через официальный портал Госуслуг.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end">
                <a href="https://www.gosuslugi.ru/600426/1/form"
                   target="_blank"
                   class="btn btn-primary btn-lg">
                    Подать заявление
                </a>
            </div>
        </div>
    </section>

</div>

<style>
    .school-home {
        --blue-main: #1557b0;
        --blue-dark: #0f3f86;
        --blue-soft: #eef5ff;
        --blue-light: #dbeafe;
        --text-main: #162033;
        --text-muted: #64748b;
        --border-soft: #dbe3ef;
        --white: #ffffff;
        --shadow: 0 8px 24px rgba(15, 63, 134, 0.08);
    }

    .home-hero,
    .info-card,
    .latest-news,
    .gosuslugi-box {
        background: var(--white);
        border: 1px solid var(--border-soft);
        border-radius: 18px;
        box-shadow: var(--shadow);
    }

    .home-hero {
        padding: 36px;
    }

    .page-label {
        display: inline-block;
        background: var(--blue-soft);
        color: var(--blue-main);
        padding: 7px 13px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
    }

    .home-title {
        font-size: 34px;
        line-height: 1.25;
        color: var(--blue-dark);
        font-weight: 800;
        margin-bottom: 18px;
    }

    .home-text {
        color: var(--text-muted);
        font-size: 17px;
        line-height: 1.7;
        margin-bottom: 24px;
    }

    .home-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .home-image-box {
        border-radius: 18px;
        overflow: hidden;
        background: var(--blue-soft);
        border: 1px solid var(--border-soft);
    }

    .home-image {
        width: 100%;
        height: 320px;
        object-fit: cover;
        display: block;
    }

    .quick-card {
        display: block;
        height: 100%;
        padding: 22px;
        background: var(--white);
        border: 1px solid var(--border-soft);
        border-radius: 16px;
        box-shadow: var(--shadow);
        text-decoration: none;
        color: var(--text-main);
        transition: 0.2s ease;
    }

    .quick-card:hover {
        transform: translateY(-3px);
        color: var(--text-main);
        box-shadow: 0 12px 30px rgba(15, 63, 134, 0.12);
    }

    .quick-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: var(--blue-soft);
        color: var(--blue-main);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        margin-bottom: 14px;
    }

    .quick-card h5 {
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 6px;
        color: var(--blue-dark);
    }

    .quick-card p {
        margin: 0;
        color: var(--text-muted);
        font-size: 14px;
    }

    .info-card {
        padding: 26px;
    }

    .info-card h4 {
        color: var(--blue-dark);
        font-weight: 800;
        margin-bottom: 18px;
    }

    .info-card p {
        color: var(--text-muted);
        line-height: 1.7;
        margin-bottom: 0;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        gap: 18px;
        padding: 13px 0;
        border-bottom: 1px solid var(--border-soft);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-row span {
        color: var(--text-muted);
    }

    .info-row strong {
        color: var(--text-main);
        text-align: right;
    }

    .blue-notice {
        margin-top: 20px;
        padding: 16px;
        border-radius: 14px;
        background: var(--blue-soft);
        border-left: 4px solid var(--blue-main);
        color: var(--text-main);
    }

    .latest-news {
        padding: 28px;
    }

    .section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 22px;
    }

    .section-head h3 {
        color: var(--blue-dark);
        font-size: 28px;
        font-weight: 800;
        margin: 8px 0 0;
    }

    .section-link {
        color: var(--blue-main);
        font-weight: 800;
        text-decoration: none;
    }

    .section-link:hover {
        color: var(--blue-dark);
        text-decoration: underline;
    }

    .news-box {
        background: var(--blue-soft);
        border: 1px solid var(--blue-light);
        border-radius: 16px;
        padding: 22px;
    }

    .news-category {
        display: inline-block;
        border-radius: 999px;
        padding: 7px 13px;
        font-size: 12px;
        font-weight: 800;
    }

    .news-content h4 {
        margin-top: 14px;
        margin-bottom: 10px;
        font-size: 25px;
        font-weight: 800;
        color: var(--blue-dark);
    }

    .news-content p {
        color: var(--text-muted);
        line-height: 1.7;
    }

    .news-date {
        font-size: 14px;
        margin-bottom: 12px;
    }

    .news-image {
        width: 100%;
        height: 240px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid var(--border-soft);
    }

    .news-empty {
        height: 240px;
        border-radius: 14px;
        background: var(--white);
        border: 1px solid var(--border-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
    }

    .gosuslugi-box {
        padding: 28px;
    }

    .gosuslugi-box h3 {
        color: var(--blue-dark);
        font-weight: 800;
        margin-top: 8px;
        margin-bottom: 10px;
    }

    .gosuslugi-box p {
        color: var(--text-muted);
        margin: 0;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .home-hero,
        .info-card,
        .latest-news,
        .gosuslugi-box {
            padding: 22px;
        }

        .home-title {
            font-size: 25px;
        }

        .home-text {
            font-size: 15px;
        }

        .home-image {
            height: 230px;
        }

        .section-head {
            flex-direction: column;
            align-items: flex-start;
        }

        .info-row {
            flex-direction: column;
            gap: 4px;
        }

        .info-row strong {
            text-align: left;
        }
    }
</style>

@endsection