@extends('layout')

@section('content')

<section class="zam-classes-page">

    <div class="admin-users-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Заместитель директора</span>
                <h3>Редактор классов учеников</h3>
            </div>

            <a href="/profile" class="section-link">← В профиль</a>
        </div>

        <p class="admin-users-hero-text">
            Здесь можно назначать ученикам класс или снимать их с класса.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-info alert-dismissible fade show mb-4">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="admin-users-search mb-4">
        <div class="admin-users-search-box">
            <span class="admin-users-search-icon">🔎</span>

            <input type="text"
                   id="classUsersSearch"
                   class="admin-users-search-input"
                   placeholder="Поиск по логину или ФИО...">
        </div>

        <div class="admin-users-count">
            Найдено: <span id="classUsersCount">{{ $users->count() }}</span>
        </div>
    </div>

    <div class="admin-users-list">
        @forelse($users as $user)
            <article class="admin-user-row class-user-row"
                     data-search="{{ mb_strtolower($user->login . ' ' . $user->full_name . ' ' . $user->studend_class) }}">

                <div class="admin-user-main">
                    <div class="admin-user-avatar-small">
                        {{ mb_substr($user->full_name ?: $user->login, 0, 1) }}
                    </div>

                    <div>
                        <h4>{{ $user->full_name }}</h4>

                        <p>
                            <strong>Логин:</strong> {{ $user->login }}<br>
                            <strong>Текущий класс:</strong>
                            {{ $user->studend_class && $user->studend_class !== 'none' ? $user->studend_class . ' класс' : 'Без класса' }}
                        </p>
                    </div>
                </div>

                <div class="admin-user-role">
                    @if($user->role === 'student')
                        <span class="admin-role-badge role-student">Ученик</span>
                    @else
                        <span class="admin-role-badge role-guest">Гость</span>
                    @endif
                </div>

                <div class="admin-user-actions">
                    <form method="POST" action="/zam/classes/update">
                        @csrf

                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <label class="admin-small-label">Класс</label>

                        <div class="admin-user-class-form">
                            <select name="class" class="form-select form-select-sm">
                                <option value="none" {{ $user->studend_class == 'none' ? 'selected' : '' }}>
                                    Без класса
                                </option>

                                @for($i = 1; $i <= 9; $i++)
                                    <option value="{{ $i }}" {{ $user->studend_class == $i ? 'selected' : '' }}>
                                        {{ $i }} класс
                                    </option>
                                @endfor
                            </select>

                            <button type="submit" class="btn btn-primary btn-sm">
                                Сохранить
                            </button>
                        </div>
                    </form>
                </div>
            </article>
        @empty
            <div class="diary-empty">
                <div class="diary-empty-icon">👥</div>
                <h4>Пользователи не найдены</h4>
                <p>Нет пользователей для назначения класса.</p>
            </div>
        @endforelse
    </div>

    <div class="diary-empty mt-4" id="classUsersEmpty" style="display: none;">
        <div class="diary-empty-icon">🔎</div>
        <h4>Пользователи не найдены</h4>
        <p>Попробуйте изменить поисковый запрос.</p>
    </div>

</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('classUsersSearch');
        const rows = document.querySelectorAll('.class-user-row');
        const countElement = document.getElementById('classUsersCount');
        const emptyBlock = document.getElementById('classUsersEmpty');

        searchInput?.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();
            let visibleCount = 0;

            rows.forEach(function (row) {
                const searchText = row.dataset.search || '';

                if (searchText.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            countElement.textContent = visibleCount;
            emptyBlock.style.display = visibleCount === 0 ? 'block' : 'none';
        });
    });
</script>

@endsection