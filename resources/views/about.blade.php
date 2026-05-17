@extends('layout')

@section('content')

<section class="about-page">

    <div class="about-main-card mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="page-label">О школе</span>

                <h2 class="about-main-title">
                    Сведения об образовательной организации
                </h2>

                <p class="about-main-text">
                    В этом разделе размещена основная информация о МБОУ «Притобольная СОШ»:
                    сведения о школе, структуре управления, официальных документах и руководстве.
                </p>
            </div>

            <div class="col-lg-4">
                <div class="about-main-info">
                    <h4>МБОУ «Притобольная СОШ»</h4>
                    <p>
                        Курганская область,<br>
                        Притобольный район,<br>
                        с. Боровлянка
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="about-block mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Разделы</span>
                <h3>Информация о школе</h3>
            </div>
        </div>

        <div class="about-links-grid">
            <a href="/about/general" class="about-link-card">
                <div class="about-link-icon">🏫</div>

                <div class="about-link-content">
                    <h4>Основные сведения</h4>
                    <p>
                        Информация о школе, учредителе, месте нахождения,
                        режиме работы и контактах.
                    </p>
                </div>

                <div class="about-link-arrow">→</div>
            </a>

            <a href="/about/structure" class="about-link-card">
                <div class="about-link-icon">👥</div>

                <div class="about-link-content">
                    <h4>Структура и органы управления</h4>
                    <p>
                        Руководство школы, заместители директора,
                        коллегиальные органы управления.
                    </p>
                </div>

                <div class="about-link-arrow">→</div>
            </a>

            <a href="/about/documents" class="about-link-card">
                <div class="about-link-icon">📄</div>

                <div class="about-link-content">
                    <h4>Документы</h4>
                    <p>
                        Устав, лицензии, локальные акты и другие
                        официальные документы школы.
                    </p>
                </div>

                <div class="about-link-arrow">→</div>
            </a>

            <a href="/about/management" class="about-link-card">
                <div class="about-link-icon">👨‍💼</div>

                <div class="about-link-content">
                    <h4>Руководство</h4>
                    <p>
                        Администрация школы, контакты руководителя
                        и информация о приёме граждан.
                    </p>
                </div>

                <div class="about-link-arrow">→</div>
            </a>
        </div>
    </div>

</section>

@endsection