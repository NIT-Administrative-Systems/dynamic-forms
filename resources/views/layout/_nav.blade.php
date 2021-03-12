{{-- You should customize this file. --}}

<ul class="navbar-nav">
    <li class="nav-item {{ Route::is('home') ? 'active' : '' }}">
        <a class="nav-link" href="/">Home</a>
    </li>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="applicantDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Applicant
        </a>
        <div class="dropdown-menu" aria-labelledby="applicantDropdown">
            <a class="dropdown-item" href="{{ route('application-discover') }}">Discover Opportunities &amp; Apply</a>
            <a class="dropdown-item" href="{{ route('applicant.application.index') }}">My Applicantions</a>
        </div>
    </li>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="programAdminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Admin
        </a>
        <div class="dropdown-menu" aria-labelledby="programAdminDropdown">
            <a class="dropdown-item" href="{{ route('admin.organization.index') }}">Organizations</a>
            <a class="dropdown-item" href="{{ route('admin.program.index') }}">Programs</a>
        </div>
    </li>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="platformAdminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Platform Admin
        </a>
        <div class="dropdown-menu" aria-labelledby="platformAdminDropdown">
            <a class="dropdown-item" href="{{ route('vapor-ui') }}">Vapor UI</a>
        </div>
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
