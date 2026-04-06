@extends('layout')

@section('content')

<div class="row">

    <div class="col-md-4">
        <img src="{{ asset('storage/school.jpg') }}" 
             class="img-fluid rounded mb-3"
             alt="Школа">
    </div>

    <div class="col-md-8">

        <h3>
            Муниципальное общеобразовательное учреждение<br>
            "Притобольная СОШ"
        </h3>

        <p>
            Официальный сайт образовательной организации. 
            Здесь размещается актуальная информация о деятельности школы.
        </p>

        <hr>

        <h4>Последняя новость</h4>

        @if($news)
        <div class="card mb-3">

            <div class="row g-0">

                <div class="col-md-8">
                    <div class="card-body">

                        <h5>{{ $news->title }}</h5>

                        <small>{{ $news->published_at }}</small>

                        <p>{{ Str::limit($news->content, 150) }}</p>

                        <span class="badge 
                            @if($news->category == 'безопасность') bg-danger
                            @elseif($news->category == 'профориентация') bg-warning
                            @else bg-success
                            @endif">
                            {{ $news->category }}
                        </span>

                        <br><br>

                        <a href="/news" class="btn btn-primary btn-sm">
                            Читать все новости
                        </a>

                    </div>
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    @if($news->image)
                        <img src="{{ asset('storage/' . $news->image) }}" 
                             style="width:100%; height:150px; object-fit:cover;"
                             class="rounded">
                    @endif
                </div>

            </div>

        </div>
        @endif

        <hr>

        <div class="text-center mt-4">

            <h5>Запись в 1 класс</h5>

            <p>
                Подать заявление на зачисление ребёнка в первый класс можно через портал Госуслуг.
            </p>

            <a href="https://www.gosuslugi.ru/600426/1/form" 
               target="_blank"
               class="btn btn-success btn-lg">
                Записаться
            </a>

        </div>

    </div>

</div>

@endsection