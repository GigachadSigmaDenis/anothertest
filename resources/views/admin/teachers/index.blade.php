@extends('layout')

@section('content')
<h2>Учителя</h2>

<a href="/admin/teachers/create" class="btn btn-success mb-3">+ Добавить</a>

<div class="row">

@foreach($teachers as $t)
<div class="col-md-3 mb-3">

    <div class="card p-2 text-center">

        @if($t->photo)
            <img src="{{ asset('storage/'.$t->photo) }}" style="width:100%; height:200px; object-fit:cover;">
        @endif

        <h5>{{ $t->full_name }}</h5>

        <small>{{ $t->subjects }}</small>

        <br>

        <a href="/admin/teachers/edit/{{ $t->id }}" class="btn btn-warning btn-sm">Редактировать</a>

        <form method="POST" action="/admin/teachers/delete/{{ $t->id }}">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm mt-1">Удалить</button>
        </form>

    </div>

</div>
@endforeach

</div>
@endsection