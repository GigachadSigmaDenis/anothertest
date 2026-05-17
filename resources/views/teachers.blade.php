@extends('layout')

@section('content')

<section class="teachers-page">

    <div class="teachers-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Педагоги</span>
                <h3>Наши учителя</h3>
            </div>
        </div>

        <p class="teachers-hero-text">
            Педагогический состав МБОУ «Притобольная СОШ». 
            Здесь можно посмотреть информацию об учителях и преподаваемых предметах.
        </p>
    </div>

    @if($teachers->count() > 0)
        <div class="teachers-toolbar mb-4">
            <div class="teachers-search-box">
                <span class="teachers-search-icon">🔎</span>

                <input type="text"
                       id="teachersSearch"
                       class="teachers-search-input"
                       placeholder="Поиск по ФИО или предметам...">
            </div>

            <div class="teachers-count">
                Найдено: <span id="teachersCount">{{ $teachers->count() }}</span>
            </div>
        </div>

        <div class="teachers-grid" id="teachersGrid">
            @foreach($teachers as $teacher)
                <div class="teacher-grid-item"
                     data-search="{{ mb_strtolower($teacher->full_name . ' ' . $teacher->subjects) }}">

                    <button type="button"
                            class="teacher-card-new"
                            onclick="showTeacherModal({{ $teacher->id }})">

                        @if($teacher->photo)
                            <div class="teacher-photo-wrap-new">
                                <img src="{{ asset('storage/' . $teacher->photo) }}"
                                     class="teacher-photo-new"
                                     alt="{{ $teacher->full_name }}">
                            </div>
                        @else
                            <div class="teacher-photo-empty-new">
                                <span>Нет фото</span>
                            </div>
                        @endif

                        <div class="teacher-card-body-new">
                            <h4>{{ $teacher->full_name }}</h4>

                            <p>
                                <strong>Предметы:</strong><br>
                                {{ \Illuminate\Support\Str::limit($teacher->subjects, 110) }}
                            </p>

                            <span class="teacher-more">
                                Подробнее →
                            </span>
                        </div>
                    </button>
                </div>
            @endforeach
        </div>

        <div class="teachers-empty" id="teachersSearchEmpty" style="display: none;">
            <div class="teachers-empty-icon">🔎</div>
            <h4>Учителя не найдены</h4>
            <p>Попробуйте изменить поисковый запрос.</p>
        </div>
    @else
        <div class="teachers-empty">
            <div class="teachers-empty-icon">👩‍🏫</div>
            <h4>Информация об учителях пока не добавлена</h4>
            <p>В этом разделе будет опубликован педагогический состав школы.</p>
        </div>
    @endif

</section>

<div id="teacherModal" class="teacher-modal-overlay" style="display: none;">
    <div class="teacher-modal-container">
        <div class="teacher-modal-content">
            <button type="button" class="teacher-modal-close" onclick="closeModal()">
                &times;
            </button>

            <div id="modalBody"></div>
        </div>
    </div>
</div>

<script>
    const teachersData = @json($teachers);

    function showTeacherModal(id) {
        const teacher = teachersData.find(t => t.id === id);
        if (!teacher) return;

        const modalBody = document.getElementById('modalBody');

        modalBody.innerHTML = `
            <div class="teacher-modal-grid">
                <div class="teacher-modal-photo-box">
                    ${teacher.photo
                        ? `<img src="/storage/${teacher.photo}" class="teacher-modal-photo" alt="${escapeHtml(teacher.full_name)}">`
                        : `<div class="teacher-modal-photo-empty"><span>Нет фото</span></div>`
                    }
                </div>

                <div class="teacher-modal-info">
                    <span class="page-label">Педагог</span>

                    <h3>${escapeHtml(teacher.full_name)}</h3>

                    <div class="teacher-modal-block">
                        <h4>Преподаваемые предметы</h4>
                        <p>${escapeHtml(teacher.subjects || 'Информация не указана')}</p>
                    </div>

                    <button type="button" class="btn btn-secondary teacher-modal-back" onclick="closeModal()">
                        ← Назад к списку
                    </button>
                </div>
            </div>
        `;

        const modal = document.getElementById('teacherModal');
        modal.style.display = 'flex';
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('teacherModal');
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
    }

    function escapeHtml(text) {
        if (!text) return '';

        const div = document.createElement('div');
        div.textContent = text;

        return div.innerHTML;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('teachersSearch');
        const teacherItems = document.querySelectorAll('.teacher-grid-item');
        const countElement = document.getElementById('teachersCount');
        const emptyBlock = document.getElementById('teachersSearchEmpty');

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = this.value.trim().toLowerCase();
                let visibleCount = 0;

                teacherItems.forEach(function (item) {
                    const searchText = item.dataset.search || '';

                    if (searchText.includes(query)) {
                        item.style.display = '';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                countElement.textContent = visibleCount;

                if (visibleCount === 0) {
                    emptyBlock.style.display = 'block';
                } else {
                    emptyBlock.style.display = 'none';
                }
            });
        }

        const modal = document.getElementById('teacherModal');

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.style.display === 'flex') {
                closeModal();
            }
        });
    });
</script>

@endsection