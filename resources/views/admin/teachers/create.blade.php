@extends('layout')

@section('content')
<h2>Добавить учителя</h2>

<form method="POST" action="/admin/teachers/store" enctype="multipart/form-data">
@csrf

<input name="full_name" class="form-control mb-2" placeholder="ФИО">

<textarea name="subjects" class="form-control mb-2" placeholder="Предметы"></textarea>

<input type="file" name="photo" id="photoInput" class="form-control mb-2">

<img id="preview" style="max-width:200px; display:none;">

<button type="button" id="cancelBtn" class="btn btn-danger btn-sm mt-2" style="display:none;">
    Отменить фото
</button>

<button class="btn btn-success mt-3">Сохранить</button>

</form>

<script>
const input = document.getElementById('photoInput');
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
    preview.style.display = 'none';
    cancelBtn.style.display = 'none';
});
</script>
@endsection