@extends('northwestern::purple-container')

@section('heading')
<div class="row">
    <div class="col-12 col-md-6">
        <h2>Programs</h2>
    </div>
    <div class="col-12 col-md-6 text-right">
        <a class="btn btn-outline-primary" href="{{ route('admin.program.create') }}">
            <i class="fas fa-plus" aria-hidden="true"></i>
            Create
        </a>
    </div>
</div>
@endsection

@section('content')
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Program</th>
            <th scope="col">Organization</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($programs as $program)
            <tr>
                <td><a href="{{ route('admin.program.show', ['program' => $program->id]) }}">{{ $program->name }}</a></td>
                <td>{{ $program->organization->name }}</td>
            </tr>
        @empty
        <tr>
            <td colspan="2"><em>No programs</em></td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
