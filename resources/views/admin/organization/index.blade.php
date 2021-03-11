@extends('northwestern::purple-container')

@section('heading')
<div class="row">
    <div class="col-12 col-md-6">
        <h2>Organizations</h2>
    </div>
    <div class="col-12 col-md-6 text-right">
        <a class="btn btn-outline-primary" href="{{ route('organization.create') }}">
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
            <th scope="col">Organization</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($organizations as $org)
            <tr>
                <td>{{-- <a href="{{ route('organization.show', ['organization' => $org->id]) }}">{{ $org->name }}</a> --}} {{ $org->name }}</td>
            </tr>
        @empty
        <tr>
            <td colspan="2" class="text-center"><em>No organizations</em></td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
