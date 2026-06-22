@extends('layout')

@section('content')
<section class="documents-page">
    <div class="documents-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Сведения об образовательной организации</span>
                <h3>Документы</h3>
            </div>
            <a href="/about" class="section-link">← Назад</a>
        </div>
        <p class="documents-hero-text">
            Документы школы сгруппированы по категориям. Используйте фильтр по категории или поиск по названию документа.
        </p>
    </div>

    <form method="GET" action="/about/documents" class="documents-filter row g-3 align-items-end mb-4">
        <div class="col-lg-5">
            <label class="form-label">Поиск</label>
            <input type="text"
                   name="q"
                   class="form-control"
                   value="{{ $search ?? '' }}"
                   placeholder="Введите название документа">
        </div>

        <div class="col-lg-5">
            <label class="form-label">Категория</label>
            <select name="category" class="form-select">
                <option value="">Все документы</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ ($currentCategory ?? '') === $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Найти</button>
            @if(($currentCategory ?? '') !== '' || ($search ?? '') !== '')
                <a href="/about/documents" class="btn btn-outline-secondary">Сброс</a>
            @endif
        </div>
    </form>

    <div class="documents-categories-filter mb-4">
        <a href="/about/documents{{ ($search ?? '') !== '' ? '?q=' . urlencode($search) : '' }}" 
           class="documents-category-filter {{ ($currentCategory ?? '') === '' ? 'active' : '' }}">
            Все документы
        </a>
        @foreach($categories as $category)
            @php
                $categoryUrl = '/about/documents?category=' . urlencode($category);
                if (($search ?? '') !== '') {
                    $categoryUrl .= '&q=' . urlencode($search);
                }
            @endphp
            <a href="{{ $categoryUrl }}" class="documents-category-filter {{ ($currentCategory ?? '') === $category ? 'active' : '' }}">
                {{ $category }}
            </a>
        @endforeach
    </div>

    @if($documents->count())
        <div class="documents-search-wrap mb-3">
            <div class="documents-count">
                Найдено документов: <span id="documentsCount">{{ $documents->count() }}</span>
            </div>
        </div>

        @foreach($documentsByCategory as $category => $categoryDocuments)
            <div class="documents-category mb-4">
                <h4 class="mb-3">{{ $category }}</h4>

                <div class="documents-grid" id="documentsGrid">
                    @foreach($categoryDocuments as $document)
                        @php
                            $href = $document->link
                                ? (str_starts_with($document->link, 'http://') || str_starts_with($document->link, 'https://')
                                    ? $document->link
                                    : asset('storage/' . $document->link))
                                : null;
                        @endphp

                        @if($href)
                            <a href="{{ $href }}"
                               target="_blank"
                               rel="noopener"
                               class="document-card"
                               data-title="{{ mb_strtolower($document->title) }}"
                               data-category="{{ $category }}">
                                <div class="document-icon">PDF</div>
                                <div class="document-info">
                                    <h4>{{ $document->title }}</h4>
                                    <p>Открыть документ</p>
                                </div>
                                <div class="document-arrow">→</div>
                            </a>
                        @else
                            <div class="document-card document-card-disabled" data-title="{{ mb_strtolower($document->title) }}" data-category="{{ $category }}">
                                <div class="document-icon">PDF</div>
                                <div class="document-info">
                                    <h4>{{ $document->title }}</h4>
                                    <p class="text-muted">Файл не указан</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="documents-empty documents-search-empty" id="documentsSearchEmpty" style="display: none;">
            <div class="documents-empty-icon">🔎</div>
            <h4>Документы не найдены</h4>
            <p>Попробуйте изменить поисковый запрос или категорию.</p>
        </div>
    @else
        <div class="documents-empty">
            <div class="documents-empty-icon">📄</div>
            <h4>Документов пока нет</h4>
            <p>В этом разделе будут опубликованы официальные документы школы.</p>
        </div>
    @endif
</section>

<style>
    /* Стили для категорий-фильтров */
    .documents-categories-filter {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .documents-category-filter {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 999px;
        border: 1px solid rgba(0, 0, 0, 0.12);
        color: inherit;
        text-decoration: none;
        background: rgba(255, 255, 255, 0.7);
        transition: all 0.2s;
        font-size: 14px;
    }

    .documents-category-filter:hover {
        background: rgba(0, 0, 0, 0.05);
        text-decoration: none;
        color: inherit;
    }

    .documents-category-filter.active {
        border-color: #0d6efd;
        color: #0d6efd;
        background: rgba(13, 110, 253, 0.08);
    }

    /* Стили для карточек документов */
    .documents-search-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .documents-count {
        font-size: 14px;
        color: #6c757d;
    }

    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
    }

    .document-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 20px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
        cursor: pointer;
        min-height: 70px;
    }

    .document-card:hover {
        border-color: #0d6efd;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.12);
        transform: translateY(-2px);
        text-decoration: none;
        color: inherit;
    }

    .document-card-disabled {
        opacity: 0.6;
        cursor: default;
    }

    .document-card-disabled:hover {
        transform: none;
        border-color: rgba(0, 0, 0, 0.08);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    }

    .document-icon {
        flex-shrink: 0;
        width: 44px;
        height: 44px;
        background: #dc3545;
        color: #fff;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .document-info {
        flex: 1;
        min-width: 0;
    }

    .document-info h4 {
        font-size: 15px;
        font-weight: 600;
        margin: 0 0 4px 0;
        line-height: 1.4;
        color: #212529;
        word-wrap: break-word;
        overflow-wrap: break-word;
        white-space: normal;
    }

    .document-info p {
        font-size: 13px;
        color: #6c757d;
        margin: 0;
    }

    .document-arrow {
        flex-shrink: 0;
        color: #adb5bd;
        font-size: 18px;
        transition: transform 0.2s;
        margin-left: auto;
    }

    .document-card:hover .document-arrow {
        transform: translateX(4px);
        color: #0d6efd;
    }

    /* Стили для пустого состояния */
    .documents-empty {
        text-align: center;
        padding: 48px 20px;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .documents-empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .documents-empty h4 {
        font-size: 20px;
        margin-bottom: 8px;
        color: #212529;
    }

    .documents-empty p {
        color: #6c757d;
        max-width: 400px;
        margin: 0 auto;
    }

    .documents-search-empty {
        display: none;
        margin-top: 16px;
    }

    /* Адаптив */
    @media (max-width: 576px) {
        .documents-grid {
            grid-template-columns: 1fr;
        }

        .documents-search-wrap {
            flex-direction: column;
            align-items: stretch;
        }

        .section-head {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .documents-categories-filter {
            gap: 6px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('documentsSearch');
        const cards = document.querySelectorAll('.document-card');
        const countElement = document.getElementById('documentsCount');
        const emptyBlock = document.getElementById('documentsSearchEmpty');

        // Создаём поле поиска, если его нет в форме
        if (!searchInput) {
            const form = document.querySelector('.documents-filter');
            if (form) {
                const searchField = form.querySelector('input[name="q"]');
                if (searchField) {
                    // Добавляем live-поиск к существующему полю
                    searchField.addEventListener('input', function () {
                        const query = this.value.trim().toLowerCase();
                        filterDocuments(query);
                    });
                }
            }
            return;
        }

        searchInput.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();
            filterDocuments(query);
        });

        function filterDocuments(query) {
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

            if (countElement) {
                countElement.textContent = visibleCount;
            }

            if (emptyBlock) {
                if (visibleCount === 0) {
                    emptyBlock.style.display = 'block';
                } else {
                    emptyBlock.style.display = 'none';
                }
            }
        }
    });
</script>
@endsection