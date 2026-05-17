@extends('layout')

@section('content')

<section class="structure-page">

    <div class="structure-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">О школе</span>
                <h3>Структура и органы управления</h3>
            </div>

            <a href="/about" class="section-link">
                ← Назад
            </a>
        </div>

        <p class="structure-hero-text">
            Управление школой осуществляется директором, заместителями директора,
            а также коллегиальными органами управления образовательной организации.
        </p>
    </div>

    <div class="structure-level mb-4">
        <div class="structure-level-title">
            <span>Руководитель образовательной организации</span>
        </div>

        <div class="structure-main-card">
            <div class="structure-avatar">👨‍💼</div>

            <div class="structure-main-content">
                <span class="structure-tag tag-director">Руководитель</span>

                <h4>Директор</h4>

                <p class="structure-name">
                    Александров Александр Александрович
                </p>
            </div>
        </div>
    </div>

    <div class="structure-level mb-4">
        <div class="structure-level-title">
            <span>Заместители директора</span>
        </div>

        <div class="structure-grid">
            <div class="structure-card">
                <div class="structure-avatar">📘</div>

                <div>
                    <span class="structure-tag tag-deputy">Заместитель</span>

                    <h4>Заместитель директора по учебной работе</h4>

                    <p class="structure-name">
                        Леонова Ольга Владимировна
                    </p>
                </div>
            </div>

            <div class="structure-card">
                <div class="structure-avatar">🌱</div>

                <div>
                    <span class="structure-tag tag-deputy">Заместитель</span>

                    <h4>Заместитель директора по воспитательной работе</h4>

                    <p class="structure-name">
                        Синицына Светлана Степановна
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="structure-level">
        <div class="structure-level-title">
            <span>Коллегиальные органы и объединения</span>
        </div>

        <div class="structure-grid structure-grid-three">
            <div class="structure-card">
                <div class="structure-avatar">👥</div>

                <div>
                    <span class="structure-tag tag-collegial">Коллегиальный орган</span>

                    <h4>Педагогический совет</h4>

                    <p class="structure-name">
                        Коллегиальный орган управления
                    </p>
                </div>
            </div>

            <div class="structure-card">
                <div class="structure-avatar">🤝</div>

                <div>
                    <span class="structure-tag tag-collegial">Коллегиальный орган</span>

                    <h4>Управляющий совет</h4>

                    <p class="structure-name">
                        Представители родителей, учителей и учащихся
                    </p>
                </div>
            </div>

            <div class="structure-card">
                <div class="structure-avatar">📚</div>

                <div>
                    <span class="structure-tag tag-method">Методическая работа</span>

                    <h4>Методические объединения</h4>

                    <p class="structure-name">
                        Объединения учителей-предметников
                    </p>
                </div>
            </div>
        </div>
    </div>

</section>

@endsection