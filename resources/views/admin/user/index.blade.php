@extends('northwestern::purple-container')

@section('heading')
    <div class="row">
        <div class="col-md-8">
            <h1>Users &amp; Roles</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('admin.user.create') }}" class="btn btn-outline-primary">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Add User
            </a>
        </div>
    </div>
@endsection

@section('content')
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">User</th>
            <th scope="col">Name</th>
            <th scope="col">Primary Affiliation</th>
            <th scope="col">Roles</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($users as $user)
            <tr>
                <td>
                    <a href="{{ route('admin.user.edit', ['user' => $user]) }}">
                        {{ $user->username }}
                    </a>
                </td>
                <td>{{ $user->full_name }}</td>
                <td>{{ $user->primary_affiliation }}</td>
                <td>{!! $user->roles->map->name->join(', ') ?: '<em>None</em>' !!}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center"><em>No users found</em></td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $users->links() }}
@endsection
