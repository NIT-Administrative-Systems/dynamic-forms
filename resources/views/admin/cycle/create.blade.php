@extends('northwestern::purple-container')

@section('heading')
<h2>Create Cycle for {{ $program->name }}</h2>
@endsection

@section('content')
@include('northwestern::errors')

<form method="post" action="{{ route('admin.cycle.store') }}">
    @csrf
    <input type="hidden" name="program_id" value="{{ $program->id }}">

    <div class="form-group">
        <label for="opensAt">Opens At</label>
        <input type="datetime-local" class="form-control" name="opens_at" id="opensAt">
    </div>

    <div class="form-group">
        <label for="closesAt">Closes At</label>
        <input type="datetime-local" class="form-control" name="closes_at" id="closesAt">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection
