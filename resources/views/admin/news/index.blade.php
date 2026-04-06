@extends('layout')

@section('content')

<h2>Новости (админ)</h2>

<a href="/admin/news/create" class="btn btn-success mb-3">+ Добавить</a>

@foreach($news as $item)
<div class="card mb-3 p-3 d-flex flex-row justify-content-between">

    <div>
        <h5>{{ $item->title }}</h5>
        <small>{{ $item->published_at }}</small>
    </div>

    <div>
        <a href="/admin/news/edit/{{ $item->id }}" class="btn btn-warning btn-sm">Редактировать</a>

        <form method="POST" action="/admin/news/delete/{{ $item->id }}" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm">Удалить</button>
        </form>
    </div>

</div>
@endforeach

@endsection