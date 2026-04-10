@extends('layout')

@section('content')
<div class="card p-4">

    <h3 class="text-center mb-4">Редактор расписания</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="text-center mb-3">
        <button class="btn btn-outline-primary" onclick="toggleFilters()">
            Фильтры ▼
        </button>
    </div>

    <div id="filters" style="display: none;">
        <form method="GET" class="row justify-content-center mb-4">

            <div class="col-md-2 mb-2">
                <label>Класс</label>
                <input name="class"
                       value="{{ request('class') }}"
                       class="form-control"
                       placeholder="Например: 11А">
            </div>

            <div class="col-md-2 mb-2">
                <label>С даты</label>
                <input type="date" name="date_from"
                       value="{{ request('date_from') }}"
                       class="form-control">
            </div>

            <div class="col-md-2 mb-2">
                <label>По дату</label>
                <input type="date" name="date_to"
                       value="{{ request('date_to') }}"
                       class="form-control">
            </div>

            <div class="col-md-2 mb-2">
                <label>Неделя (начало)</label>
                <input type="date" name="week_start"
                       value="{{ $weekStart->format('Y-m-d') }}"
                       class="form-control">
            </div>

            <div class="col-md-2 mb-2">
                <label>День</label>
                <select name="day" class="form-control">
                    <option value="">Все</option>
                    @foreach($days as $d)
                        <option value="{{ $d }}" {{ request('day') == $d ? 'selected' : '' }}>
                            {{ $d }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end mb-2">
                <button class="btn btn-primary w-100">Применить</button>
            </div>

        </form>
    </div>

    <div class="text-center mb-3">
        <a href="/teacher/schedule/create" class="btn btn-success">
            Добавить день
        </a>
    </div>

    @php
        $safeClass = request('class');
    @endphp

    @foreach($days as $day)

        @if(!request('day') || request('day') == $day)

        <div class="card mb-4 p-3">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5>{{ $day }}</h5>

                <a href="/teacher/schedule/edit?day={{ $day }}&week_start={{ $weekStart->format('Y-m-d') }}&class={{ $class }}" class="btn btn-sm btn-primary">
                    Редактировать
                </a>
            </div>

            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>Урок</th>
                        <th>Предмет</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i=1; $i<=7; $i++)
                        <tr>
                            <td width="10%"><strong>{{ $i }}</strong></td>
                            <td>{{ $schedule[$day][$i] ?? '-' }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>

        </div>

        @endif

    @endforeach

</div>

<script>
function toggleFilters() {
    let el = document.getElementById('filters');
    if (el.style.display === 'none' || el.style.display === '') {
        el.style.display = 'block';
    } else {
        el.style.display = 'none';
    }
}
</script>

@endsection