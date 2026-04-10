@extends('layout')

@section('content')

<h2 class="text-center mb-4">Наши учителя</h2>

<div class="row" id="teachersGrid">
    @foreach($teachers as $teacher)
    <div class="col-md-4 col-lg-3 mb-4">
        <div class="card teacher-card h-100" onclick="showTeacherModal({{ $teacher->id }})">
            @if($teacher->photo)
                <img src="{{ asset('storage/' . $teacher->photo) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
            @else
                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                    <span class="text-white">Нет фото</span>
                </div>
            @endif
            <div class="card-body d-flex flex-column">
                <h5 class="card-title text-center">{{ $teacher->full_name }}</h5>
                <p class="card-text small">
                    <strong>Предметы:</strong><br>
                    {{ Str::limit($teacher->subjects, 100) }}
                </p>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div id="teacherModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">&times;</button>
            <div id="modalBody"></div>
            <div class="text-center mt-4">
                <button class="btn btn-secondary" onclick="closeModal()">← Назад к списку</button>
            </div>
        </div>
    </div>
</div>

<style>
    .teacher-card {
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #e0e0e0;
    }
    
    .teacher-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        border-color: #4e73df;
    }
    
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow-y: auto;
        padding: 20px;
    }
    
    .modal-container {
        position: relative;
        max-width: 500px;
        width: 100%;
        margin: auto;
    }
    
    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 30px;
        position: relative;
        animation: modalFadeIn 0.3s ease;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }
    
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .modal-close {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 28px;
        cursor: pointer;
        background: none;
        border: none;
        color: #999;
        transition: color 0.3s;
        line-height: 1;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-close:hover {
        color: #333;
    }
    
    .teacher-photo-full {
        width: 100%;
        max-height: 350px;
        object-fit: contain;
        border-radius: 12px;
        margin: 0 auto 20px auto;
        display: block;
        background: #f5f5f5;
    }
    
    .teacher-name-full {
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 15px;
        text-align: center;
        color: #2c3e50;
    }
    
    .teacher-subjects-full {
        font-size: 15px;
        line-height: 1.6;
        color: #555;
        text-align: center;
    }
    
    .teacher-subjects-full strong {
        color: #2c3e50;
        display: block;
        margin-bottom: 8px;
    }
    
    body.modal-open {
        overflow: hidden;
    }
</style>

<script>
    const teachersData = @json($teachers);
    
    function showTeacherModal(id) {
        const teacher = teachersData.find(t => t.id === id);
        if (!teacher) return;
        
        const modalBody = document.getElementById('modalBody');
        
        modalBody.innerHTML = `
            <div>
                ${teacher.photo ? 
                    `<img src="/storage/${teacher.photo}" class="teacher-photo-full" alt="${teacher.full_name}">` : 
                    `<div class="teacher-photo-full bg-secondary d-flex align-items-center justify-content-center" style="height: 250px; border-radius: 12px;">
                        <span class="text-white">Нет фото</span>
                    </div>`
                }
                <div class="teacher-name-full">${escapeHtml(teacher.full_name)}</div>
                <div class="teacher-subjects-full">
                    <strong>Преподаваемые предметы</strong>
                    ${escapeHtml(teacher.subjects)}
                </div>
            </div>
        `;
        
        const modal = document.getElementById('teacherModal');
        modal.style.display = 'flex';
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        const modal = document.getElementById('teacherModal');
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    document.getElementById('teacherModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('teacherModal');
            if (modal && modal.style.display === 'flex') {
                closeModal();
            }
        }
    });
</script>

@endsection