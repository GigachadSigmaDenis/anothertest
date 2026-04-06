@extends('layout')

@section('content')

<h2>Редактировать новость</h2>

<form method="POST" action="/admin/news/update/{{ $news->id }}" enctype="multipart/form-data">
@csrf

<input 
    name="title" 
    class="form-control mb-2" 
    value="{{ $news->title }}"
>

<select name="category" class="form-control mb-2">
    <option value="безопасность" {{ $news->category == 'безопасность' ? 'selected' : '' }}>Безопасность</option>
    <option value="профориентация" {{ $news->category == 'профориентация' ? 'selected' : '' }}>Профориентация</option>
    <option value="образование" {{ $news->category == 'образование' ? 'selected' : '' }}>Образование</option>
</select>

<input 
    type="datetime-local" 
    name="published_at"
    class="form-control mb-2"
    value="{{ $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('Y-m-d\TH:i') : '' }}"
>

<textarea name="content" id="editor" class="form-control mb-2">
    {{ $news->content }}
</textarea>

@if($news->image)
    <img src="{{ asset('storage/'.$news->image) }}" style="max-width:200px;" class="mb-2">
@endif

<input type="file" name="image" id="imageInput" class="form-control mb-2">

<img id="preview" style="max-width:200px; display:none; border-radius:10px;" />

<button type="button" id="cancelBtn" class="btn btn-danger btn-sm mt-2" style="display:none;">
    Отменить картинку
</button>

<br>

<button class="btn btn-success mt-3">Обновить</button>

</form>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>

<script>
tinymce.init({
    selector: '#editor'
});

const input = document.getElementById('imageInput');
const preview = document.getElementById('preview');
const cancelBtn = document.getElementById('cancelBtn');

input.addEventListener('change', function () {
    const file = this.files[0];

    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
        cancelBtn.style.display = 'inline-block';
    }
});

cancelBtn.addEventListener('click', function () {
    input.value = "";
    preview.src = "";
    preview.style.display = 'none';
    cancelBtn.style.display = 'none';
});
</script>

@endsection