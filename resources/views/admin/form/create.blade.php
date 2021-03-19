@extends('northwestern::purple-container')

@section('heading')
<div class="row">
    <div class="col-12 col-md-8">
        <h2>{{ $program->name }} &mdash; {{ $form_type->name }}</h2>
    </div>

    <div class="col-12 col-md-4 text-right">
        <form method="post" action="{{ route('admin.form.store') }}">
            @csrf
            <input type="hidden" name="program_id" value="{{ $program->id }}">
            <input type="hidden" name="form_type_id" value="{{ $form_type->id }}">
            <input type="hidden" name="definition" id="definition" value="">

            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-save" aria-hidden="true"></i>
                Save Form
            </button>
        </form>
    </div>
</div>
@endsection

@section('content')
<p>There should be some "required" fields and information aboot them in here, depending on the form type.</p>
<p>This isn't wired up with any auto-save stuff atm, but it probably ought to be. Clicking the button should publish your work.</p>
@include('northwestern::errors')

<div id="formio-builder"></div>
@endsection

@push('scripts')
@include('admin.form._formio-script')
@endpush
