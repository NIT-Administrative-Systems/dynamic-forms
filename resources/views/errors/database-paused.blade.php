@extends('northwestern::purple-container')

@section('heading')
<h2 class="comic-heading text-center">You caught me napping!</h2>
@endsection

@section('content')
<div class="row">
    <div class="col-8 mx-auto">
        <div class="alert alert-warning" role="alert">
            <p>The database in our <code>{{ config('app.env') }}</code> site goes to sleep when it's been idle. This saves us quite a bit of money!</p>
            <p class="mb-0">Please wait a second and refresh the page. The database only takes a moment to come back online.</p>
        </div>
    </div>
</div>
@endsection
