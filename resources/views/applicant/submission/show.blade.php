@extends('northwestern::purple-container')

@section('heading')
<h2>{{ $submission->form_version->form->type->name }} for application #{{ $submission->application->id }}</h2>
@endsection

@section('content')
<pre id="submission-stuff"></pre>
<div id="formio-form"></div>
@endsection

@push('scripts')
<script lang="text/javascript">
window.onload = function() {
    Formio.icons = 'fontawesome'; // @TODO move this
    Formio.createForm(document.getElementById('formio-form'), {!! $definition !!}, {readOnly: true}).then(function (form) {
        form.submission = {
            data: {!! $data !!},
        };
    });
};
</script>
@endpush
