@extends('layout')

@section('content')

<h2>Редактировать новость</h2>

<form method="POST" action="/admin/news/update/{{ $news->id }}" enctype="multipart/form-data">
@csrf

<input 
    name="title" 
    class="form-control mb-2" 
    value="{{ $news->title }}"
    required
>

<select name="category" class="form-control mb-2" required>
    <option value="безопасность" {{ $news->category == 'безопасность' ? 'selected' : '' }}>Безопасность</option>
    <option value="профориентация" {{ $news->category == 'профориентация' ? 'selected' : '' }}>Профориентация</option>
    <option value="образование" {{ $news->category == 'образование' ? 'selected' : '' }}>Образование</option>
</select>

<input 
    type="datetime-local" 
    name="published_at"
    class="form-control mb-2"
    value="{{ $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('Y-m-d\TH:i') : '' }}"
    required
>

<textarea 
    name="content" 
    id="editor" 
    class="form-control mb-2" 
    rows="10" 
    placeholder="Текст новости..."
    style="white-space: pre-wrap; word-wrap: break-word; resize: vertical;"
>{{ $news->content }}</textarea>

@if($news->image)
    <div class="mb-2">
        <p>Текущее изображение:</p>
        <img src="{{ asset('storage/'.$news->image) }}" style="max-width:200px; border-radius:10px;">
    </div>
@endif

<input type="file" name="image" id="imageInput" class="form-control mb-2" accept="image/*">

<img id="preview" style="max-width:200px; display:none; border-radius:10px; margin-top:10px;" />

<button type="button" id="cancelBtn" class="btn btn-danger btn-sm mt-2" style="display:none;">
    Отменить картинку
</button>

<br>

<button class="btn btn-success mt-3">Обновить</button>
<a href="/admin/news" class="btn btn-secondary mt-3">Отмена</a>

</form>

<script>
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