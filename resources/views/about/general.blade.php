@extends('layout')

@section('content')

<section class="page-section general-section">
    <div class="section-head">
        <div>
            <span class="page-label">О школе</span>
            <h3>Основные сведения</h3>
        </div>

        <a href="/about" class="section-link">
            ← Назад
        </a>
    </div>

    <div class="general-grid">
        <div class="general-card general-card-wide">
            <div class="general-icon">📘</div>
            <div>
                <h4>Полное наименование</h4>
                <p>
                    Муниципальное бюджетное общеобразовательное учреждение
                    «Притобольная средняя общеобразовательная школа»
                </p>
            </div>
        </div>

        <div class="general-card">
            <div class="general-icon">📄</div>
            <div>
                <h4>Сокращённое наименование</h4>
                <p>МБОУ «Притобольная СОШ»</p>
            </div>
        </div>

        <div class="general-card">
            <div class="general-icon">📅</div>
            <div>
                <h4>Дата создания</h4>
                <p>1985 год</p>
            </div>
        </div>

        <div class="general-card">
            <div class="general-icon">🏛️</div>
            <div>
                <h4>Учредитель</h4>
                <p>Администрация Притобольного района</p>
            </div>
        </div>

        <div class="general-card">
            <div class="general-icon">⏰</div>
            <div>
                <h4>Режим работы</h4>
                <p>
                    Понедельник — пятница<br>
                    08:00 — 16:00
                </p>
            </div>
        </div>

        <div class="general-card">
            <div class="general-icon">📞</div>
            <div>
                <h4>Контакты</h4>
                <p>
                    Телефон: 8 (35239) 9-37-05<br>
                    Email: pritschool@mail.ru
                </p>
            </div>
        </div>

        <div class="general-card">
            <div class="general-icon">📍</div>
            <div>
                <h4>Место нахождения</h4>
                <p>
                    Курганская область, Притобольный район,<br>
                    с. Боровлянка, ул. Центральная, 6
                </p>
            </div>
        </div>
    </div>

    <div class="map-card mt-4">
        <div class="map-header">
            <div>
                <span class="page-label">Карта</span>
                <h4>Как нас найти</h4>
            </div>

            <a href="https://www.openstreetmap.org/?mlat=51.407666&mlon=83.763126#map=18/51.407666/83.763126"
               target="_blank"
               class="btn btn-primary btn-sm">
                Открыть карту
            </a>
        </div>

        <div class="map-frame-wrap">
            <iframe
                class="map-frame"
                src="https://www.openstreetmap.org/export/embed.html?bbox=83.758126%2C51.404666%2C83.768126%2C51.410666&layer=mapnik&marker=51.407666%2C83.763126"
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

@endsection