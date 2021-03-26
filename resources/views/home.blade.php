@extends('northwestern::purple-container')

@section('heading')
<h1 class="text-center comic-heading">Welcome to Grantman's latest adventure!</h1>
@endsection

@section('content')
<div class="card-deck">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title comic-text">What is this?</h2>
            <p class="card-text">This is the <code>{{ config('app.env') }}</code> site for a future grant management system. It serves as a testbed for some of our latest &amp; greatest inventions.</p>
            <p class="card-text">Be aware that <strong>nothing you do here is permanent</strong>. This site is deleted and re-created regularly, so any program configs or applications will be deleted.</p>
            <p class="card-text">So feel free to play around -- change things, delete things, whatever!</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 class="card-title comic-text">Things to do!</h2>
            <ul class="card-text">
                <li>
                    Check out the <a href="{{ route('admin.form.edit', ['form' => 2]) }}">form builder</a>
                    <ul>
                        <li>Have a look at <a href="">what the applicant will see</a></li>
                        <li>And <a href="https://teams.microsoft.com/l/chat/0/0?users=nick.evans@northwestern.edu">let us know what you think</a> of the tech</li>
                    </ul>
                    <li class="mt-3">Check back later. All of the other pages are placeholders for now; we're focused on the form builder</li>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
