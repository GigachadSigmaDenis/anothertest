@extends('layout')

@section('content')

<div class="card p-4">
    <h3 class="text-center mb-4">Документы</h3>

    @php
        $documents = App\Models\Document::where('is_published', true)
                    ->orderBy('sort_order')
                    ->orderBy('id', 'desc')
                    ->get();
    @endphp

    @if($documents->count() > 0)
        <div class="row g-3">
            @foreach($documents as $document)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ $document->link }}" target="_blank" class="text-decoration-none d-block document-link">
                        <div class="card p-3 text-center h-100 document-card">
                            <div class="document-content">
                                <div class="document-icon">
                                    PDF
                                </div>
                                <h6 class="mb-0 mt-3">{{ $document->title }}</h6>
                                <small class="text-muted mt-2 d-block">Открыть документ →</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center">
            Документов пока нет
        </div>
    @endif
</div>

<style>
    .document-link {
        display: block;
        height: 100%;
    }
    
    .document-card {
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
        background: white;
    }
    
    .document-link:hover .document-card {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        border-color: #4e73df;
        background: #f8f9ff;
    }
    
    .document-icon {
        width: 60px;
        height: 60px;
        background: #dc3545;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        border-radius: 8px;
        font-size: 14px;
        font-weight: bold;
    }
    
    .document-content {
        width: 100%;
    }
</style>

@endsection