@extends('layout')

@section('content')
<section class="auth-page auth-register-page">
    <div class="auth-card">
        <div class="auth-card-header">
            <span class="page-label">Аккаунт</span>
            <h3>Регистрация</h3>
            <p>Заполните данные. Почту можно не указывать.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-info mb-3">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <strong>Проверьте форму:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/register" class="auth-form" autocomplete="off">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="loginInput">Логин</label>
                <input id="loginInput"
                       class="form-control @error('login') is-invalid @enderror"
                       name="login"
                       value="{{ old('login') }}"
                       placeholder="Введите логин"
                       required>
                @error('login')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="fullNameInput">ФИО</label>
                <input id="fullNameInput"
                       class="form-control @error('full_name') is-invalid @enderror"
                       name="full_name"
                       value="{{ old('full_name') }}"
                       placeholder="Иванов Иван Иванович"
                       pattern="[А-Яа-яЁё ]+"
                       maxlength="255"
                       required>
                <small class="text-muted d-block mt-1">Только кириллица и пробелы.</small>
                @error('full_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="emailInput">Email <span class="text-muted fw-normal">необязательно</span></label>
                <input id="emailInput"
                       type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="mail@example.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label" for="passwordInput">Пароль</label>
                <input id="passwordInput"
                       type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password"
                       placeholder="Минимум 4 символа"
                       required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary w-100" type="submit">Зарегистрироваться</button>

            <div class="auth-card-footer">
                Уже есть аккаунт? <a href="/login">Войти</a>
            </div>
        </form>
    </div>
</section>

<style>
.auth-page {
    display: flex;
    justify-content: center;
    padding: 28px 0 44px;
}

.auth-card {
    width: min(100%, 520px);
    background: #ffffff;
    border: 1px solid #dbe3ef;
    border-radius: 22px;
    box-shadow: 0 14px 34px rgba(15, 63, 134, 0.12);
    padding: 28px;
    color: #162033;
}

.auth-card-header {
    margin-bottom: 22px;
}

.auth-card-header h3 {
    margin: 6px 0 8px;
}

.auth-card-header p {
    margin: 0;
    color: #64748b;
}

.auth-card-footer {
    margin-top: 18px;
    text-align: center;
    color: #64748b;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fullNameInput = document.getElementById('fullNameInput');

    if (!fullNameInput) return;

    fullNameInput.addEventListener('input', function () {
        const caret = this.selectionStart;
        const originalLength = this.value.length;

        this.value = this.value
            .replace(/[^А-Яа-яЁё\s]/gu, '')
            .replace(/\s{2,}/g, ' ')
            .replace(/^\s+/, '');

        const diff = originalLength - this.value.length;
        this.setSelectionRange(Math.max(caret - diff, 0), Math.max(caret - diff, 0));
    });

    fullNameInput.addEventListener('blur', function () {
        this.value = this.value.trim().replace(/\s{2,}/g, ' ');
    });
});
</script>
@endsection
