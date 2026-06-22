@extends('layout')

@section('content')
@php
    $teachersForModal = $teachers->map(function ($teacher) {
        return [
            'id' => $teacher->id,
            'full_name' => $teacher->full_name ?: $teacher->name,
            'photo' => $teacher->photo,
            'subjects' => $teacher->subject_list,
            'subjects_inline' => $teacher->subjects_inline,
        ];
    })->values();
@endphp

<section class="teachers-page">
    <div class="teachers-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Педагоги</span>
                <h3>Наши учителя</h3>
            </div>
        </div>
        <p class="teachers-hero-text">
            Педагогический состав МБОУ «Притобольная СОШ». Здесь можно посмотреть информацию об учителях и преподаваемых предметах.
        </p>
    </div>

    @if($teachers->count() > 0)
        <div class="teachers-toolbar mb-4">
            <div class="teachers-search-box">
                <span class="teachers-search-icon"></span>
                <input type="text" id="teachersSearch" class="teachers-search-input" placeholder="Поиск по ФИО или предметам...">
            </div>
            <div class="teachers-count">
                Найдено: <span id="teachersCount">{{ $teachers->count() }}</span>
            </div>
        </div>

        <div class="teachers-grid" id="teachersGrid">
            @foreach($teachers as $teacher)
                @php
                    $teacherName = $teacher->full_name ?: $teacher->name;
                    $searchData = mb_strtolower($teacherName . ' ' . $teacher->subjects_inline);
                @endphp

                <div class="teacher-grid-item" data-search="{{ $searchData }}">
                    <button type="button" class="teacher-card teacher-card-clickable" onclick="showTeacherModal({{ $teacher->id }})">
                        <div class="teacher-card-inner">
                            @if($teacher->photo)
                                <div class="teacher-photo-wrap">
                                    <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacherName }}" class="teacher-photo">
                                </div>
                            @else
                                <div class="teacher-photo-placeholder">
                                    {{ mb_substr($teacherName, 0, 1) }}
                                </div>
                            @endif

                            <div class="teacher-card-body">
                                <h4 class="teacher-name">{{ $teacherName }}</h4>

                                <div class="teacher-subjects">
                                    @forelse(array_slice($teacher->subject_list, 0, 3) as $subject)
                                        <span class="teacher-subject-badge">{{ $subject }}</span>
                                    @empty
                                        <span class="teacher-subject-badge teacher-subject-badge-default">Учитель начальных классов</span>
                                    @endforelse

                                    @if(count($teacher->subject_list) > 3)
                                        <span class="teacher-subject-badge teacher-subject-badge-more">+{{ count($teacher->subject_list) - 3 }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </button>
                </div>
            @endforeach
        </div>

        <div id="teachersEmpty" class="alert alert-secondary mt-4" style="display:none;">
            По вашему запросу учителя не найдены.
        </div>
    @else
        <div class="alert alert-secondary">Учителя пока не добавлены.</div>
    @endif
</section>

<div id="teacherModal" class="teacher-modal-overlay" aria-hidden="true">
    <div class="teacher-modal-container" role="dialog" aria-modal="true" aria-labelledby="teacherModalTitle">
        <button type="button" class="teacher-modal-close" onclick="closeTeacherModal()" aria-label="Закрыть окно">&times;</button>
        <div id="teacherModalBody"></div>
    </div>
</div>

<div id="teacherPhotoModal" class="teacher-photo-modal-overlay" aria-hidden="true">
    <div class="teacher-photo-modal-container" role="dialog" aria-modal="true" aria-label="Полное фото учителя">
        <button type="button" class="teacher-photo-modal-close" onclick="closeTeacherPhotoModal()" aria-label="Закрыть фото">&times;</button>
        <img id="teacherPhotoModalImage" src="" alt="" class="teacher-photo-modal-image">
        <div id="teacherPhotoModalCaption" class="teacher-photo-modal-caption"></div>
    </div>
</div>

<style>
.teachers-page {
    padding: 0 0 40px 0;
}

.teachers-hero {
    margin-bottom: 24px;
}

.teachers-hero-text {
    color: #6c757d;
    max-width: 700px;
    margin-top: 8px;
}

.teachers-toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: center;
    justify-content: space-between;
}

.teachers-search-box {
    flex: 1;
    min-width: 200px;
    position: relative;
}

.teachers-search-input {
    width: 100%;
    padding: 10px 16px;
    border: 1px solid #dbe3ef;
    border-radius: 12px;
    font-size: 16px;
    background: #ffffff;
    color: #162033;
    transition: border-color 0.2s;
}

.teachers-search-input:focus {
    outline: none;
    border-color: #1557b0;
}

.teachers-count {
    font-size: 14px;
    color: #6c757d;
    white-space: nowrap;
}

.teachers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
}

.teacher-grid-item {
    display: flex;
}

.teacher-card {
    display: flex;
    width: 100%;
    border: 0;
    padding: 0;
    background: transparent;
    cursor: pointer;
    text-align: left;
}

.teacher-card-inner {
    display: flex;
    flex-direction: column;
    width: 100%;
    background: transparent;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.2s ease;
}

.teacher-card-clickable:hover .teacher-card-inner {
    transform: translateY(-2px);
}

.teacher-photo-wrap {
    width: 100%;
    aspect-ratio: 4 / 5;
    overflow: hidden;
    border-radius: 12px;
    background: #eef5ff;
}

.teacher-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center center;
    display: block;
}

.teacher-photo-placeholder {
    width: 100%;
    aspect-ratio: 4 / 5;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef5ff;
    color: #0f3f86;
    font-size: 48px;
    font-weight: 700;
    border-radius: 12px;
}

.teacher-card-body {
    padding: 12px 4px 4px 4px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.teacher-name {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #0f3f86;
    line-height: 1.3;
    overflow-wrap: anywhere;
}

.teacher-subjects {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 8px;
}

.teacher-subject-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    border: 1px solid rgba(21, 87, 176, 0.18);
    background: #eef5ff;
    color: #0f3f86;
    font-size: 12px;
    line-height: 1.2;
    overflow-wrap: anywhere;
}

.teacher-subject-badge-default {
    background: #f0f7ff;
    border-color: #c5d8f0;
    color: #0f3f86;
    font-weight: 600;
}

.teacher-subject-badge-more {
    background: #ffffff;
    border-color: #dbe3ef;
}

/* Модальное окно */
.teacher-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 2000;
    align-items: center;
    justify-content: center;
    padding: 24px;
    background: rgba(15, 32, 51, 0.55);
}

.teacher-modal-overlay.is-open {
    display: flex;
}

.teacher-modal-container {
    position: relative;
    width: min(920px, 100%);
    max-height: calc(100vh - 48px);
    overflow-y: auto;
    background: #ffffff;
    color: #162033;
    border-radius: 24px;
    border: 1px solid #dbe3ef;
    box-shadow: 0 26px 70px rgba(15, 63, 134, 0.24);
    padding: 28px;
}

.teacher-modal-close {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 40px;
    height: 40px;
    border: 0;
    border-radius: 50%;
    background: #eef5ff;
    color: #0f3f86;
    font-size: 28px;
    line-height: 1;
    cursor: pointer;
}

.teacher-modal-grid {
    display: grid;
    grid-template-columns: 240px minmax(0, 1fr);
    gap: 28px;
    align-items: start;
}

.teacher-modal-photo-box {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.teacher-modal-photo,
.teacher-modal-photo-empty {
    width: 100%;
    aspect-ratio: 4 / 5;
    border-radius: 20px;
    object-fit: cover;
    object-position: center center;
}

.teacher-modal-photo-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef5ff;
    color: #0f3f86;
    font-weight: 700;
    border: 1px dashed #c6d8f2;
}

.teacher-modal-photo-button {
    display: block;
    width: 100%;
    padding: 0;
    border: 0;
    background: transparent;
    cursor: zoom-in;
    border-radius: 20px;
}

.teacher-modal-photo-hint {
    margin-top: 8px;
    color: #64748b;
    font-size: 12px;
    text-align: center;
}

.teacher-modal-info {
    min-width: 0;
}

.teacher-modal-info h3 {
    margin: 8px 0 18px;
    font-size: 24px;
    color: #0f3f86;
}

.teacher-modal-block {
    border: 1px solid #dbe3ef;
    border-radius: 18px;
    padding: 16px 18px;
    background: #f8fbff;
}

.teacher-modal-block h4 {
    margin: 0 0 12px 0;
    font-size: 14px;
    font-weight: 600;
    color: #0f3f86;
}

.teacher-modal-subjects {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.teacher-modal-actions {
    margin-top: 18px;
}

/* Фото модалка */
.teacher-photo-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 2300;
    align-items: center;
    justify-content: center;
    padding: 22px;
    background: rgba(15, 32, 51, 0.82);
}

.teacher-photo-modal-overlay.is-open {
    display: flex;
}

.teacher-photo-modal-container {
    position: relative;
    width: min(980px, 100%);
    max-height: calc(100vh - 44px);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}

.teacher-photo-modal-image {
    display: block;
    max-width: 100%;
    max-height: calc(100vh - 110px);
    width: auto;
    height: auto;
    object-fit: contain;
    object-position: center center;
    border-radius: 18px;
    background: #ffffff;
    box-shadow: 0 26px 70px rgba(0, 0, 0, 0.32);
}

.teacher-photo-modal-caption {
    max-width: 100%;
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.94);
    color: #162033;
    font-weight: 700;
    overflow-wrap: anywhere;
    text-align: center;
}

.teacher-photo-modal-close {
    position: absolute;
    top: -12px;
    right: -12px;
    width: 42px;
    height: 42px;
    border: 0;
    border-radius: 50%;
    background: #ffffff;
    color: #0f3f86;
    font-size: 28px;
    line-height: 1;
    cursor: pointer;
    z-index: 1;
}

/* Адаптив */
@media (max-width: 768px) {
    .teachers-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 16px;
    }

    .teacher-modal-overlay {
        padding: 12px;
        align-items: flex-start;
    }

    .teacher-modal-container {
        padding: 22px 18px;
        max-height: calc(100vh - 24px);
    }

    .teacher-modal-grid {
        grid-template-columns: 1fr;
    }

    .teacher-modal-photo-box {
        max-width: 200px;
        margin: 0 auto;
    }

    .teacher-photo-modal-overlay {
        padding: 12px;
    }

    .teacher-photo-modal-close {
        top: 8px;
        right: 8px;
    }

    .teacher-photo-modal-image {
        max-height: calc(100vh - 96px);
        border-radius: 14px;
    }
}

@media (max-width: 480px) {
    .teachers-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
    }

    .teacher-name {
        font-size: 14px;
    }

    .teacher-subject-badge {
        font-size: 11px;
        padding: 3px 8px;
    }
}
</style>

<script>
const teachersData = @json($teachersForModal);
const searchInput = document.getElementById('teachersSearch');
const items = Array.from(document.querySelectorAll('.teacher-grid-item'));
const count = document.getElementById('teachersCount');
const empty = document.getElementById('teachersEmpty');
const modal = document.getElementById('teacherModal');
const modalBody = document.getElementById('teacherModalBody');
const photoModal = document.getElementById('teacherPhotoModal');
const photoModalImage = document.getElementById('teacherPhotoModalImage');
const photoModalCaption = document.getElementById('teacherPhotoModalCaption');

function showTeacherModal(id) {
    const teacher = teachersData.find(item => Number(item.id) === Number(id));

    if (!teacher || !modal || !modalBody) {
        return;
    }

    const subjects = Array.isArray(teacher.subjects) && teacher.subjects.length
        ? teacher.subjects.map(subject => `<span class="teacher-subject-badge">${escapeHtml(subject)}</span>`).join('')
        : '<span class="teacher-subject-badge teacher-subject-badge-default">Учитель начальных классов</span>';

    modalBody.innerHTML = `
        <div class="teacher-modal-grid">
            <div class="teacher-modal-photo-box">
                ${teacher.photo
                    ? `<button type="button" class="teacher-modal-photo-button" data-photo-url="/storage/${escapeAttribute(teacher.photo)}" data-photo-caption="${escapeAttribute(teacher.full_name)}" aria-label="Открыть полное фото учителя ${escapeAttribute(teacher.full_name)}">
                            <img src="/storage/${escapeAttribute(teacher.photo)}" class="teacher-modal-photo" alt="${escapeAttribute(teacher.full_name)}">
                       </button>
                       <div class="teacher-modal-photo-hint">Нажмите на фото для увеличения</div>`
                    : `<div class="teacher-modal-photo-empty"><span>Нет фото</span></div>`}
            </div>
            <div class="teacher-modal-info">
                <span class="page-label">Педагог</span>
                <h3 id="teacherModalTitle">${escapeHtml(teacher.full_name)}</h3>
                <div class="teacher-modal-block">
                    <h4>Преподаваемые предметы</h4>
                    <div class="teacher-modal-subjects">${subjects}</div>
                </div>
                <div class="teacher-modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeTeacherModal()">← Назад к списку</button>
                </div>
            </div>
        </div>
    `;

    const photoButton = modalBody.querySelector('.teacher-modal-photo-button');
    if (photoButton) {
        photoButton.addEventListener('click', event => {
            event.stopPropagation();
            openTeacherPhotoModal(photoButton.dataset.photoUrl, photoButton.dataset.photoCaption);
        });
    }

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
}

function closeTeacherModal() {
    if (!modal) return;

    closeTeacherPhotoModal(false);
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
}

function openTeacherPhotoModal(src, caption) {
    if (!photoModal || !photoModalImage || !photoModalCaption || !src) return;

    photoModalImage.src = src;
    photoModalImage.alt = caption || 'Фото учителя';
    photoModalCaption.textContent = caption || '';
    photoModal.classList.add('is-open');
    photoModal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
}

function closeTeacherPhotoModal(restoreScroll = true) {
    if (!photoModal) return;

    photoModal.classList.remove('is-open');
    photoModal.setAttribute('aria-hidden', 'true');

    if (photoModalImage) {
        photoModalImage.removeAttribute('src');
        photoModalImage.alt = '';
    }

    if (photoModalCaption) {
        photoModalCaption.textContent = '';
    }

    if (restoreScroll && (!modal || !modal.classList.contains('is-open'))) {
        document.body.style.overflow = '';
    }
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function escapeAttribute(value) {
    return escapeHtml(value).replaceAll('`', '&#096;');
}

if (searchInput) {
    searchInput.addEventListener('input', () => {
        const query = searchInput.value.trim().toLowerCase();
        let visible = 0;

        items.forEach(item => {
            const match = item.dataset.search.includes(query);
            item.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        count.textContent = visible;
        empty.style.display = visible === 0 ? '' : 'none';
    });
}

if (modal) {
    modal.addEventListener('click', event => {
        if (event.target === modal) {
            closeTeacherModal();
        }
    });
}

if (photoModal) {
    photoModal.addEventListener('click', event => {
        if (event.target === photoModal) {
            closeTeacherPhotoModal();
        }
    });
}

document.addEventListener('keydown', event => {
    if (event.key !== 'Escape') return;

    if (photoModal && photoModal.classList.contains('is-open')) {
        closeTeacherPhotoModal();
        return;
    }

    if (modal && modal.classList.contains('is-open')) {
        closeTeacherModal();
    }
});
</script>
@endsection