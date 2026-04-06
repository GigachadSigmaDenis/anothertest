@extends('layout')

@section('content')
<h2>Редактировать учителя</h2>

<form method="POST" action="/admin/teachers/update/{{ $teacher->id }}" enctype="multipart/form-data">
@csrf

<input name="name" value="{{ $teacher->full_name }}" class="form-control mb-2">

<textarea name="subjects" class="form-control mb-2">{{ $teacher->subjects }}</textarea>

@if($teacher->photo)
    <img src="{{ asset('storage/'.$teacher->photo) }}" style="max-width:200px;">
@endif

<input type="file" name="photo" id="photoInput" class="form-control mb-2">

<img id="preview" style="max-width:200px; display:none;">

<button type="button" id="cancelBtn" class="btn btn-danger btn-sm mt-2" style="display:none;">
    Отменить фото
</button>

<button class="btn btn-success mt-3">Обновить</button>

</form>
@endsection