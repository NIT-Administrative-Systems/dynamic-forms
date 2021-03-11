@extends('northwestern::purple-container')

@section('heading')
<h2>Create Organization</h2>
@endsection

@section('content')
@include('northwestern::errors')

<form method="post" action="{{ route('organization.store') }}">
    @csrf
    <div class="form-group">
        <label for="name">Organization Name</label>
        <input type="text" class="form-control" name="name" id="name">
    </div>

    <div class="form-group">
        <label for="name">Program Slug</label>
        <input type="text" class="form-control" name="slug" id="slug" aria-describedby="slugHelp">
        <small id="slugHelp" class="form-text text-muted">The slug is a URL-friendly version of your organization name. It has to be unique.</small>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection
