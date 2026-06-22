@extends('layout')

@section('content')
<section class="auth-page auth-login-page">
    <div class="auth-card">
        <div class="auth-card-header">
            <span class="page-label">Аккаунт</span>
            <h3>Вход</h3>
            <p>Введите логин и пароль для входа на сайт.</p>
        </div>

        {{-- Используем только один способ вывода ошибок --}}
        @if($errors->any())
            <div class="alert alert-danger mb-3">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        <form method="POST" action="/login" class="auth-form">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="loginInput">Логин</label>
                <input id="loginInput"
                       class="form-control"
                       name="login"
                       value="{{ old('login') }}"
                       placeholder="Введите логин"
                       required>
            </div>

            <div class="mb-4">
                <label class="form-label" for="passwordInput">Пароль</label>
                <input id="passwordInput"
                       type="password"
                       class="form-control"
                       name="password"
                       placeholder="Введите пароль"
                       required>
            </div>

            <button class="btn btn-primary w-100" type="submit">Войти</button>

            <div class="auth-card-footer">
                Нет аккаунта? <a href="/register">Зарегистрироваться</a>
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
@endsection