@extends('northwestern::purple-container')

@section('heading')
<h1 class="text-center pb-4">How do you want to log in?</h1>
@endsection

@section('content')
<div class="card-deck">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Northwestern NetID</h2>
            <p class="card-text">Students, faculty, and staff can log in with their netID.</p>
            <p class="card-text">Note: SSO will <em>probably</em> be the "default" option, unless visiting a specific link we sent out to non-NU sponsors. Eventually.</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('login-sso') }}" class="btn btn-lg btn-block btn-outline-primary mb-0 card-link">Northwestern NetID</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Sponsor from a Partner University</h2>
            <p class="card-text">If you are a faculty member at a partner school, you should have previously set up a non-Northwestern sponsor account.</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('login') }}" class="btn btn-lg btn-block btn-outline-primary mb-0 card-link">Partner University Sponsor Login</a>
        </div>
    </div>
</div>
@endsection
