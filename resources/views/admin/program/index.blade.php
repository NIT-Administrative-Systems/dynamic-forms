@extends('northwestern::purple-container')

@section('heading')
<h2>Programs</h2>
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
                <td><a href="{{ route('program.edit', ['program' => $program->id]) }}">{{ $program->name }}</a></td>
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
