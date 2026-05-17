@extends('layout')

@section('content')

<section class="management-page">

    <div class="management-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">О школе</span>
                <h3>Руководство</h3>
            </div>

            <a href="/about" class="section-link">
                ← Назад
            </a>
        </div>

        <p class="management-hero-text">
            Информация о руководителе образовательной организации, контактные данные,
            часы приёма и сведения о профессиональной деятельности.
        </p>
    </div>

    <div class="management-card">
        <div class="management-photo-box">
            <img src="{{ asset('storage/Director.png') }}"
                 class="management-photo"
                 alt="Александров Александр Александрович">
        </div>

        <div class="management-content">
            <div class="management-header">
                <span class="management-label">Директор школы</span>

                <h2>Александров Александр Александрович</h2>

                <p>
                    Руководитель МБОУ «Притобольная средняя общеобразовательная школа»
                </p>
            </div>

            <div class="management-contacts">
                <div class="management-contact-item">
                    <span>Телефон</span>
                    <a href="tel:+73523993705">8 (35239) 9-37-05</a>
                </div>

                <div class="management-contact-item">
                    <span>Электронная почта</span>
                    <a href="mailto:pritschool@mail.ru">pritschool@mail.ru</a>
                </div>

                <div class="management-contact-item">
                    <span>Часы приёма</span>
                    <strong>Понедельник — пятница: с 8:30 до 16:00</strong>
                </div>
            </div>

            <div class="management-info-list">
                <div class="management-info-item">
                    <h4>Преподаваемые учебные предметы, курсы, дисциплины</h4>
                    <p>Основы безопасности жизнедеятельности</p>
                </div>

                <div class="management-info-item">
                    <h4>Уровень профессионального образования</h4>
                    <p>Высшее</p>
                </div>

                <div class="management-info-item">
                    <h4>Сведения о профессиональной переподготовке</h4>
                    <p>Учитель ОБЖ</p>
                </div>

                <div class="management-info-item">
                    <h4>Сведения о продолжительности опыта работы</h4>
                    <p>
                        Стаж работы — 46 лет<br>
                        Стаж работы по специальности — 46 лет
                    </p>
                </div>

                <div class="management-info-item">
                    <h4>Наименование образовательной программы</h4>
                    <p>Рабочая программа ОБЖ</p>
                </div>

                <div class="management-info-item">
                    <h4>Квалификация</h4>
                    <p>Соответствие</p>
                </div>

                <div class="management-info-item">
                    <h4>Направление подготовки / специальность</h4>
                    <p>Учитель физической культуры</p>
                </div>
            </div>
        </div>
    </div>

</section>

@endsection