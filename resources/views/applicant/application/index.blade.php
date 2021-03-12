@extends('northwestern::purple-container')

@section('heading')
<h2>My Applications</h2>
@endsection

@section('content')
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Program</th>
            <th scope="col">Organization</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($apps as $app)
            <tr>
                <td><a href="{{ route('applicant.application.show', ['application' => $app]) }}">{{ $app->id }}</a></td>
                <td>{{ $app->cycle->program->name }}</td>
                <td>{{ $app->cycle->program->organization->name }}</td>
            </tr>
        @empty
        <tr>
            <td colspan="2" class="text-center"><em>No organizations</em></td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
