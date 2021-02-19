@extends('northwestern::purple-container')

@section('content')
<p>TODO: make me pretty!</p>

<ul>
    <li><a href="{{ route('login-sso') }}">NetID SSO</a></li>
    <li><a href="{{ route('login') }}">Non-NU Login</a></li>
</ul>
@endsection
