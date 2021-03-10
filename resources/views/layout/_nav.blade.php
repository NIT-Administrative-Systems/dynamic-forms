{{-- You should customize this file. --}}

<ul class="navbar-nav">
    <li class="nav-item {{ Route::is('home') ? 'active' : '' }}">
        <a class="nav-link" href="/">Home</a>
    </li>

    <li class="nav-item {{ Route::is('admin/program.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('program.index') }}">Programs</a>
    </li>

</ul>

<div class='mt-2 mt-md-0 ml-auto'>
    <ul class="navbar-nav mr-auto">
        @auth
        <li class='nav-item'>
            <span class='nav-link'>{{ auth()->user()->full_name }}</span>
        </li>
        <li class='nav-item'>
            <a class="nav-link" href="{{  route('logout-type') }}">Logout</a>
        </li>
        @endauth

        @guest
        <li class='nav-item'>
            <a class="nav-link" href="{{ route('login-type') }}">Login</a>
        </li>
        @endguest
    </ul>
</div>
