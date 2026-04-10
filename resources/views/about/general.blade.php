@extends('layout')

@section('content')

<div class="card p-4">
    <h3 class="text-center mb-4">Основные сведения</h3>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card bg-light p-3 h-100">
                <div class="text-center mb-2">
                    <span style="font-size: 32px;">🗐</span>
                </div>
                <h5 class="text-center mb-3">Полное наименование</h5>
                <p class="text-center mb-0">Муниципальное бюджетное общеобразовательное учреждение «Притобольная средняя общеобразовательная школа»</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-light p-3 h-100">
                <div class="text-center mb-2">
                    <span style="font-size: 32px;">🗎</span>
                </div>
                <h5 class="text-center mb-3">Сокращенное наименование</h5>
                <p class="text-center mb-0">МБОУ «Притобольная СОШ»</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-light p-3 h-100">
                <div class="text-center mb-2">
                    <span style="font-size: 32px;">🗓</span>
                </div>
                <h5 class="text-center mb-3">Дата создания</h5>
                <p class="text-center mb-0">1985 год</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-light p-3 h-100">
                <div class="text-center mb-2">
                    <span style="font-size: 32px;">🗣</span>
                </div>
                <h5 class="text-center mb-3">Учредитель</h5>
                <p class="text-center mb-0">Администрация Притобольного района</p>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card bg-light p-3">
                <div class="text-center mb-2">
                    <span style="font-size: 32px;">⚲</span>
                </div>
                <h5 class="text-center mb-3">Место нахождения</h5>
                <p class="text-center mb-0">
                    Курганская область, Притобольный район,<br>
                    с. Боровлянка, ул. Центральная, 6
                </p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-light p-3 h-100">
                <div class="text-center mb-2">
                    <span style="font-size: 32px;">⏱</span>
                </div>
                <h5 class="text-center mb-3">Режим работы</h5>
                <p class="text-center mb-0">Понедельник - пятница<br>08:00 - 16:00</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-light p-3 h-100">
                <div class="text-center mb-2">
                    <span style="font-size: 32px;">☏</span>
                </div>
                <h5 class="text-center mb-3">Контакты</h5>
                <p class="text-center mb-0">
                    Телефон: 8 (35239) 9-37-05<br>
                    Email: pritschool@mail.ru
                </p>
            </div>
        </div>
    </div>
</div>

@endsection