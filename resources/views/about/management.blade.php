@extends('layout')

@section('content')

<div class="card p-4">
    <h3 class="text-center mb-4">Руководство</h3>

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="text-center mb-4">
                <img src="https://ui-avatars.com/api/?name=Александр+Александрович+Александров&background=4e73df&color=fff&size=150" 
                     class="rounded-circle mb-3"
                     style="width: 150px; height: 150px; object-fit: cover;">
                <h4>Александров Александр Александрович</h4>
                <p class="text-muted">Директор школы</p>
            </div>

            <table class="table table-bordered">
                <tr>
                    <th width="35%">Должность</th>
                    <td>Директор</td>
                </tr>
                <tr>
                    <th>Телефон</th>
                    <td>8 (35239) 9-37-05</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>pritschool@mail.ru</td>
                </tr>
                <tr>
                    <th>Часы приема</th>
                    <td>Понедельник - пятница: 8:30 - 16:00</td>
                </tr>
            </table>
        </div>
    </div>
</div>

@endsection