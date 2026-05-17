@extends('layout')

@section('content')

<section class="admin-users-page">

    <div class="admin-users-hero mb-4">
        <div class="section-head">
            <div>
                <span class="page-label">Админ-панель</span>
                <h3>Редактор пользователей</h3>
            </div>

            <a href="/admin/dashboard" class="section-link">
                ← Назад
            </a>
        </div>

        <p class="admin-users-hero-text">
            Здесь можно искать пользователей, менять роли, назначать класс ученикам
            и удалять ненужные аккаунты.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="admin-users-search mb-4">
        <div class="admin-users-search-box">
            <span class="admin-users-search-icon">🔎</span>

            <input type="text"
                   id="usersSearch"
                   class="admin-users-search-input"
                   placeholder="Поиск по логину, ФИО или email...">
        </div>

        <div class="admin-users-count">
            Найдено: <span id="usersCount">{{ $users->count() }}</span>
        </div>
    </div>

    <div class="admin-users-list" id="usersList">
        @forelse($users as $user)
            <article class="admin-user-row"
                     data-search="{{ mb_strtolower($user->login . ' ' . $user->full_name . ' ' . $user->email . ' ' . $user->role . ' ' . $user->studend_class) }}">

                <div class="admin-user-main">
                    <div class="admin-user-avatar-small">
                        {{ mb_substr($user->full_name ?: $user->login, 0, 1) }}
                    </div>

                    <div>
                        <h4>{{ $user->full_name }}</h4>

                        <p>
                            <strong>Логин:</strong> {{ $user->login }}<br>
                            <strong>Email:</strong> {{ $user->email }}
                        </p>
                    </div>
                </div>

                <div class="admin-user-role">
                    @if($user->role === 'admin')
                        <span class="admin-role-badge role-admin">Админ</span>
                    @elseif($user->role === 'teacher')
                        <span class="admin-role-badge role-teacher">Учитель</span>
                    @elseif($user->role === 'student')
                        <span class="admin-role-badge role-student">Ученик</span>
                    @elseif($user->role === 'zam_dir')
                        <span class="admin-role-badge role-zam">Зам. директора</span>
                    @else
                        <span class="admin-role-badge role-guest">Гость</span>
                    @endif

                    <span class="admin-user-class">
                        Класс:
                        @if($user->studend_class && $user->studend_class !== 'none')
                            {{ $user->studend_class }}
                        @else
                            —
                        @endif
                    </span>
                </div>

                <div class="admin-user-actions">
                    @if($user->role !== 'admin')
                        <form method="POST" action="/admin/users/update-role">
                            @csrf

                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                            <label class="admin-small-label">Роль</label>

                            <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="guest" {{ $user->role == 'guest' ? 'selected' : '' }}>Гость</option>
                                <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Ученик</option>
                                <option value="teacher" {{ $user->role == 'teacher' ? 'selected' : '' }}>Учитель</option>
                                <option value="zam_dir" {{ $user->role == 'zam_dir' ? 'selected' : '' }}>Зам. директора</option>
                            </select>
                        </form>

                        @if($user->role !== 'teacher' && $user->role !== 'zam_dir')
                            <form method="POST" action="/admin/users/update-class">
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
                                        OK
                                    </button>
                                </div>
                            </form>
                        @endif

                        <button type="button"
                                class="btn btn-danger btn-sm"
                                onclick="openDeleteUserModal(
                                    '{{ $user->id }}',
                                    '{{ e($user->full_name) }}'
                                )">
                            Удалить
                        </button>
                    @else
                        <div class="admin-user-protected">
                            Администратор защищён от изменений
                        </div>
                    @endif
                </div>
            </article>
        @empty
            <div class="admin-users-empty">
                <div class="admin-users-empty-icon">👤</div>
                <h4>Пользователи не найдены</h4>
                <p>В системе пока нет зарегистрированных пользователей.</p>
            </div>
        @endforelse
    </div>

    <div class="admin-users-empty" id="usersSearchEmpty" style="display: none;">
        <div class="admin-users-empty-icon">🔎</div>
        <h4>Пользователи не найдены</h4>
        <p>Попробуйте изменить поисковый запрос.</p>
    </div>

</section>

<div id="deleteUserModal" class="admin-delete-modal" style="display: none;">
    <div class="admin-delete-modal-box">
        <button type="button" class="admin-delete-modal-close" onclick="closeDeleteUserModal()">
            &times;
        </button>

        <div class="admin-delete-modal-icon">
            🗑
        </div>

        <h3>Удалить пользователя?</h3>

        <p>
            Вы действительно хотите удалить пользователя:
            <br>
            <strong id="deleteUserTitle"></strong>
        </p>

        <div class="admin-delete-modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteUserModal()">
                Отмена
            </button>

            <form method="POST" id="deleteUserForm">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">
                    Удалить
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('usersSearch');
        const rows = document.querySelectorAll('.admin-user-row');
        const countElement = document.getElementById('usersCount');
        const emptyBlock = document.getElementById('usersSearchEmpty');
        const deleteModal = document.getElementById('deleteUserModal');

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

            if (visibleCount === 0) {
                emptyBlock.style.display = 'block';
            } else {
                emptyBlock.style.display = 'none';
            }
        });

        deleteModal?.addEventListener('click', function (event) {
            if (event.target === deleteModal) {
                closeDeleteUserModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && deleteModal && deleteModal.style.display === 'flex') {
                closeDeleteUserModal();
            }
        });
    });

    function openDeleteUserModal(id, title) {
        const modal = document.getElementById('deleteUserModal');
        const form = document.getElementById('deleteUserForm');
        const titleElement = document.getElementById('deleteUserTitle');

        form.action = '/admin/users/delete/' + id;
        titleElement.textContent = title;

        modal.style.display = 'flex';
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteUserModal() {
        const modal = document.getElementById('deleteUserModal');

        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
    }
</script>

@endsection