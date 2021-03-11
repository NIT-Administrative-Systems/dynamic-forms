@extends('northwestern::purple-container')

@section('heading')
<h2>Grants &amp; Programs</h2>
@endsection

@section('content')
<div class="card-deck pb-4">
    @foreach ($cycles as $cycle)
        @php
        $link = route('application-form', [
            'organization' => $cycle->program->organization,
            'program' => $cycle->program,
            'cycle' => $cycle->id,
        ]);
        @endphp

        @if ($loop->iteration % 3 === 1)
        </div>
        <div class="card-deck pb-4">
        @endif

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $cycle->program->name }}</h5>
                <h6 class="card-subtitle text-muted mb-2">{{ $cycle->program->organization->name }}</h6>
                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                <p class="card-text"><small class="text-muted">Due {{ $cycle->closes_at }}</small> </p>
            </div>
            <div class="card-footer">
                <a href="{{ $link }}" class="card-link btn btn-block btn-outline-primary mb-0">Apply</a>
            </div>
        </div>
    @endforeach
</div>
@endsection
