@extends('layout')

@section('content')

<h2>Наши учителя</h2>

<div class="row">

@foreach($teachers as $teacher)

<div class="col-md-4 mb-3">

    <div class="card">

        @if($teacher->photo)
            <img src="{{ asset('storage/' . $teacher->photo) }}" class="card-img-top" style="height:250px; object-fit:cover;">
        @endif

        <div class="card-body">

            <h5 class="card-title">
                {{ $teacher->full_name }}
            </h5>

            <p><strong>Предметы:</strong></p>

            <p>
                {{ $teacher->subjects }}
            </p>

        </div>
    </div>

</div>

@endforeach

</div>

@endsection