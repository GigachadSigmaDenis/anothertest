@extends('layout')

@section('content')

<div class="card p-4">
    <h3 class="text-center mb-4">Структура и органы управления</h3>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="list-group">
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Директор</h5>
                            <p class="mb-0 text-muted">Александров Александр Александрович</p>
                        </div>
                        <span class="badge bg-primary">Руководитель</span>
                    </div>
                </div>

                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Заместитель директора по учебной работе</h5>
                            <p class="mb-0 text-muted">Леонова Ольга Владимировна</p>
                        </div>
                        <span class="badge bg-info">Заместитель</span>
                    </div>
                </div>

                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Заместитель директора по воспитательной работе</h5>
                            <p class="mb-0 text-muted">Синицына Светлана Степановна</p>
                        </div>
                        <span class="badge bg-info">Заместитель</span>
                    </div>
                </div>

                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Педагогический совет</h5>
                            <p class="mb-0 text-muted">Коллегиальный орган управления</p>
                        </div>
                        <span class="badge bg-success">Коллегиальный</span>
                    </div>
                </div>

                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Управляющий совет</h5>
                            <p class="mb-0 text-muted">Представители родителей, учителей и учащихся</p>
                        </div>
                        <span class="badge bg-success">Коллегиальный</span>
                    </div>
                </div>

                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Методические объединения</h5>
                            <p class="mb-0 text-muted">Учителей-предметников</p>
                        </div>
                        <span class="badge bg-secondary">Методические</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection