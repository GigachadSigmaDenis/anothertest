@extends('layout')

@section('content')

<div class="card p-4">
    <h2 class="text-center mb-4">Добавить учителя</h2>

    <form method="POST" action="/admin/teachers/store" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold">ФИО учителя</label>
            <input type="text" name="full_name" class="form-control" placeholder="Иванов Иван Иванович" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Предметы</label>
            <textarea name="subjects" class="form-control" rows="4" placeholder="Математика, Алгебра, Геометрия" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Фотография</label>
            <input type="file" name="photo" id="photoInput" class="form-control" accept="image/*">
            <small class="text-muted">Рекомендуемый размер: 300x300px</small>
        </div>

        <div class="mb-3">
            <img id="preview" style="max-width: 200px; display: none; border-radius: 8px; border: 1px solid #ddd; padding: 5px;">
        </div>

        <button type="button" id="cancelBtn" class="btn btn-danger btn-sm mb-3" style="display: none;">Отменить фото</button>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success-admin">Сохранить</button>
            <a href="/admin/teachers" class="btn btn-secondary">Отмена</a>
        </div>
    </form>
</div>

<script>
    const input = document.getElementById('photoInput');
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
    .btn-success-admin {
        background: #28a745;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-success-admin:hover {
        background: #218838;
        transform: translateY(-2px);
        color: white;
    }
    
    .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px;
    }
    
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.25);
    }
    
    .btn {
        border-radius: 8px;
        padding: 8px 20px;
    }
</style>

@endsection