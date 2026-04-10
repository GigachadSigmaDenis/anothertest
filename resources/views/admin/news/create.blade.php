@extends('layout')

@section('content')

<div class="card p-4">
    <h2 class="text-center mb-4">Создать новость</h2>

    <form method="POST" action="/admin/news/store" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold">Заголовок</label>
            <input type="text" name="title" class="form-control" placeholder="Введите заголовок" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Категория</label>
            <select name="category" class="form-select" required>
                <option value="безопасность">Безопасность</option>
                <option value="профориентация">Профориентация</option>
                <option value="образование">Образование</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Дата и время публикации</label>
            <input type="datetime-local" name="published_at" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Текст новости</label>
            <textarea name="content" id="editor" class="form-control" rows="12" placeholder="Введите текст новости..."></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Изображение</label>
            <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
            <small class="text-muted">Рекомендуемый размер: 800x600px</small>
        </div>

        <div class="mb-3">
            <img id="preview" style="max-width: 200px; display: none; border-radius: 8px; border: 1px solid #ddd; padding: 5px;">
        </div>

        <button type="button" id="cancelBtn" class="btn btn-danger btn-sm mb-3" style="display: none;">Отменить изображение</button>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Сохранить</button>
            <a href="/admin/news" class="btn btn-secondary">Отмена</a>
        </div>
    </form>
</div>

<script>
    const input = document.getElementById('imageInput');
    const preview = document.getElementById('preview');
    const cancelBtn = document.getElementById('cancelBtn');

    input.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
            cancelBtn.style.display = 'inline-block';
        }
    });

    cancelBtn.addEventListener('click', function() {
        input.value = "";
        preview.src = "";
        preview.style.display = 'none';
        cancelBtn.style.display = 'none';
    });
</script>

<style>
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.25);
    }
    
    .btn {
        border-radius: 8px;
        padding: 8px 20px;
    }
</style>

@endsection