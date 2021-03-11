@extends('northwestern::purple-container')

@section('heading')
<h2>Apply for {{ $program->name }}</h2>
@endsection

@section('content')
<pre id="submission-stuff"></pre>
<div id="formio-form"></div>
@endsection

@push('scripts')
<script lang="text/javascript">
window.onload = function() {
    Formio.icons = 'fontawesome'; // @TODO move this
    Formio.createForm(document.getElementById('formio-form'), {!! $definition !!}).then(function (form) {
        form.on('submit', function (submission) {
            // metadata may be useful (includes the timezone)
            document.getElementById('submission-stuff').innerHTML = 'Form data\n----------\n\n' + JSON.stringify(submission.data, null, 4);
            form.emit('submitDone', submission);
        });
    });
};
</script>
@endpush
