@extends('layout')

@section('content')

<div class="row g-4">
    <div class="col-md-6">
        <a href="/about/general" class="text-decoration-none">
            <div class="card p-4 text-center h-100" style="transition: all 0.3s ease; cursor: pointer;">
                <h4>Основные сведения</h4>
                <p class="text-muted mb-0">Информация о школе, учредителе, месте нахождения</p>
            </div>
        </a>
    </div>

    <div class="col-md-6">
        <a href="/about/structure" class="text-decoration-none">
            <div class="card p-4 text-center h-100" style="transition: all 0.3s ease; cursor: pointer;">
                <h4>Структура и органы управления</h4>
                <p class="text-muted mb-0">Руководство и педагогический состав</p>
            </div>
        </a>
    </div>

    <div class="col-md-6">
        <a href="/about/documents" class="text-decoration-none">
            <div class="card p-4 text-center h-100" style="transition: all 0.3s ease; cursor: pointer;">
                <h4>Документы</h4>
                <p class="text-muted mb-0">Устав, лицензии, локальные акты</p>
            </div>
        </a>
    </div>

    <div class="col-md-6">
        <a href="/about/management" class="text-decoration-none">
            <div class="card p-4 text-center h-100" style="transition: all 0.3s ease; cursor: pointer;">
                <h4>Руководство</h4>
                <p class="text-muted mb-0">Администрация школы, контакты</p>
            </div>
        </a>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        border-color: #4e73df;
    }
    
    .card {
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
</style>

@endsection