@extends('layout')

@section('content')

<section class="documents-page">

    <div class="documents-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">О школе</span>
                <h3>Документы</h3>
            </div>

            <a href="/about" class="section-link">
                ← Назад
            </a>
        </div>

        <p class="documents-hero-text">
            В разделе размещены официальные документы образовательной организации:
            устав, локальные акты, положения, лицензии и другие материалы.
        </p>
    </div>

    @php
        $documents = App\Models\Document::where('is_published', true)
                    ->orderBy('sort_order')
                    ->orderBy('id', 'desc')
                    ->get();
    @endphp

    <div class="documents-section">
        @if($documents->count() > 0)

            <div class="documents-search-wrap mb-4">
                <div class="documents-search-box">
                    <span class="documents-search-icon">🔎</span>

                    <input type="text"
                           id="documentsSearch"
                           class="documents-search-input"
                           placeholder="Поиск по документам...">
                </div>

                <div class="documents-count">
                    Найдено: <span id="documentsCount">{{ $documents->count() }}</span>
                </div>
            </div>

            <div class="documents-grid" id="documentsGrid">
                @foreach($documents as $document)
                    <a href="{{ $document->link }}"
                       target="_blank"
                       class="document-card"
                       data-title="{{ mb_strtolower($document->title) }}">

                        <div class="document-icon">
                            PDF
                        </div>

                        <div class="document-info">
                            <h4>{{ $document->title }}</h4>

                            <p>
                                Открыть документ
                            </p>
                        </div>

                        <div class="document-arrow">
                            →
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="documents-empty documents-search-empty" id="documentsSearchEmpty" style="display: none;">
                <div class="documents-empty-icon">🔎</div>

                <h4>Документы не найдены</h4>

                <p>
                    Попробуйте изменить поисковый запрос.
                </p>
            </div>

        @else
            <div class="documents-empty">
                <div class="documents-empty-icon">📄</div>

                <h4>Документов пока нет</h4>

                <p>
                    В этом разделе будут опубликованы официальные документы школы.
                </p>
            </div>
        @endif
    </div>

</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('documentsSearch');
        const cards = document.querySelectorAll('.document-card');
        const countElement = document.getElementById('documentsCount');
        const emptyBlock = document.getElementById('documentsSearchEmpty');

        if (!searchInput) return;

        searchInput.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();
            let visibleCount = 0;

            cards.forEach(function (card) {
                const title = card.dataset.title || '';

                if (title.includes(query)) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            countElement.textContent = visibleCount;

            if (visibleCount === 0) {
                emptyBlock.style.display = 'block';
            } else {
                emptyBlock.style.display = 'none';
            }
        });
    });
</script>

@endsection