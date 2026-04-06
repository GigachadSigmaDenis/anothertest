@extends('layout')
    }
</style>

@section('content')

<div class="schedule-card">

<h2>📅 Расписание</h2>

<p class="text-muted">Неделя с {{ $weekStart }}</p>

<div class="mb-3">
    <strong>Класс:</strong><br>

    @foreach($classes as $cls)
        <a href="/schedule?class={{ $cls }}"
        class="class-btn {{ $cls == $class ? 'active' : '' }}">
            {{ $cls }} класс
        </a>
    @endforeach

    @if($classes->isEmpty())
        <p class="text-danger mt-2">Нет расписания на эту неделю</p>
    @endif
</div>

<div class="mb-3">
    <strong>День:</strong><br>

    @php
        $selectedDay = request('day');
    @endphp

    <a href="/schedule?class={{ $class }}" class="day-btn {{ !$selectedDay ? 'active' : '' }}">Все</a>

    @foreach($days as $day)
        <a href="/schedule?class={{ $class }}&day={{ $day }}"
           class="day-btn {{ $selectedDay == $day ? 'active' : '' }}">
            {{ $day }}
        </a>
    @endforeach
</div>

<h5 class="mb-3">Класс: <strong>{{ $class }}</strong></h5>

<table class="table table-bordered text-center align-middle">

    <thead>
        <tr>
            <th>Урок</th>
            @foreach($days as $day)
                @if(!$selectedDay || $selectedDay == $day)
                    <th>{{ $day }}</th>
                @endif
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach($lessons as $lesson)
            <tr>
                <td><strong>{{ $lesson }}</strong></td>

                @foreach($days as $day)
                    @if(!$selectedDay || $selectedDay == $day)
                        <td>
                            {{ $data[$lesson][$day] ?? '-' }}
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>

</table>

</div>

@endsection

</html>