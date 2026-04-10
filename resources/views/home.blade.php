@extends('layout')

@section('content')

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <img src="{{ asset('storage/school.jpg') }}" 
                 class="img-fluid rounded"
                 alt="Школа">
        </div>
    </div>

    <div class="col-md-8">
        <div class="card p-4">
            <h3 class="text-center mb-3">
                Муниципальное общеобразовательное учреждение<br>
                "Притобольная СОШ"
            </h3>

            <p class="text-center">
                Официальный сайт образовательной организации. 
                Здесь размещается актуальная информация о деятельности школы.
            </p>
        </div>
    </div>
</div>

<div class="card p-4 mt-4">
    <h4 class="text-center mb-4">Последняя новость</h4>

    @if($news)
        <div class="row">
            <div class="col-md-8">
                <div class="card-body">
                    <h5>{{ $news->title }}</h5>
                    
                    <p class="text-muted small">
                        <strong>Дата публикации:</strong> {{ \Carbon\Carbon::parse($news->published_at)->format('d.m.Y H:i') }}
                    </p>

                    <p>{{ Str::limit(strip_tags($news->content), 200) }}</p>

                    <span class="badge 
                        @if($news->category == 'безопасность') bg-danger
                        @elseif($news->category == 'профориентация') bg-warning
                        @else bg-success
                        @endif">
                        {{ $news->category }}
                    </span>

                    <br><br>

                    <a href="/news" class="btn btn-primary">
                        Читать все новости →
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                @if($news->image)
                    <img src="{{ asset('storage/' . $news->image) }}" 
                         class="img-fluid rounded"
                         style="width:100%; height:200px; object-fit:cover;"
                         alt="{{ $news->title }}">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height:200px;">
                        <span class="text-muted">Нет изображения</span>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-info text-center">
            Новостей пока нет
        </div>
    @endif
</div>

<div class="card p-4 mt-4 text-center">
    <h5>Запись в 1 класс</h5>

    <p class="mb-3">
        Подать заявление на зачисление ребёнка в первый класс можно через портал Госуслуг.
    </p>

    <div>
        <a href="https://www.gosuslugi.ru/600426/1/form" 
           target="_blank"
           class="btn btn-success btn-lg">
            Записаться через Госуслуги
        </a>
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .btn {
        border-radius: 8px;
    }
    
    .badge {
        font-size: 12px;
        padding: 5px 10px;
    }
    
    img {
        border-radius: 8px;
    }
</style>

@endsection