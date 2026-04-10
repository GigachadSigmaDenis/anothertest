@extends('layout')

@section('content')

@php
    $selectedDay = request('day');
@endphp

<div class="card p-4">
    <h2 class="text-center mb-4">Расписание уроков</h2>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label fw-bold">Класс</label>
            <select id="classSelect" class="form-select" onchange="window.location.href=this.value">
                <option value="">Выберите класс</option>
                @for($i=1; $i<=9; $i++)
                    <option value="/schedule?class={{ $i }}" {{ $class == $i ? 'selected' : '' }}>
                        {{ $i }} класс
                    </option>
                @endfor
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">День недели</label>
            <select id="daySelect" class="form-select" onchange="window.location.href=this.value">
                <option value="/schedule?class={{ $class }}">Все дни</option>
                @foreach($days as $day)
                    <option value="/schedule?class={{ $class }}&day={{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>
                        {{ $day }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">Неделя</label>
            <input type="date" id="weekStart" class="form-control" value="{{ \Carbon\Carbon::parse($weekStart)->format('Y-m-d') }}">
        </div>
    </div>

    <div class="alert alert-info text-center mb-4">
        <strong>Неделя:</strong> {{ \Carbon\Carbon::parse($weekStart)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($weekStart)->copy()->addDays(4)->format('d.m.Y') }}
        <br>
        <strong>Класс:</strong> {{ $class }} класс
        @if(request('day'))
            <br><strong>День:</strong> {{ request('day') }}
        @endif
    </div>

    @if($class)
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle schedule-table">
                <thead class="table-primary">
                    <tr>
                        <th width="80">Урок</th>
                        @foreach($days as $day)
                            @if(!$selectedDay || $selectedDay == $day)
                                <th>{{ $day }}</th>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $lessonNumbers = [1,2,3,4,5,6,7];
                    @endphp
                    @foreach($lessonNumbers as $lesson)
                        <tr>
                            <td class="fw-bold bg-light">{{ $lesson }}</td>
                            @foreach($days as $day)
                                @if(!$selectedDay || $selectedDay == $day)
                                    <td class="subject-cell">
                                        @php
                                            $subject = $data[$lesson][$day] ?? '-';
                                        @endphp
                                        @if($subject != '-')
                                            <span class="subject-badge">{{ $subject }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(empty($data))
            <div class="alert alert-warning text-center mt-3">
                Расписание для этого класса еще не добавлено
            </div>
        @endif
    @else
        <div class="alert alert-info text-center">
            Выберите класс для просмотра расписания
        </div>
    @endif
</div>

<style>
    .schedule-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .schedule-table thead th {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
        font-weight: 600;
        padding: 12px;
        border: none;
    }
    
    .schedule-table tbody tr:hover {
        background: #f8f9ff;
    }
    
    .schedule-table td {
        vertical-align: middle;
        padding: 12px;
        border-color: #e0e0e0;
    }
    
    .subject-badge {
        display: inline-block;
        background: #e8f0fe;
        color: #224abe;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }
    
    select.form-select {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px;
    }
    
    select.form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.25);
    }
</style>

<script>
    document.getElementById('weekStart')?.addEventListener('change', function() {
        const classValue = '{{ $class }}';
        const dayValue = '{{ request('day') }}';
        let url = '/schedule?class=' + classValue + '&week_start=' + this.value;
        if (dayValue) {
            url += '&day=' + dayValue;
        }
        window.location.href = url;
    });
</script>

@endsection