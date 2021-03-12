@extends('northwestern::purple-container')

@section('heading')
<div class="row">
    <div class="col-12 col-md-8">
        <h2>Apply for {{ $program->name }}</h2>
    </div>
    <div class="col-12 col-md-4 text-right">
        <form method="post" action="{{ route('applicant.submission.update', ['submission' => $submission]) }}">
            @csrf
            @method('put')
            <input type="hidden" name="data" id="data">

            <button class="btn btn-outline-primary">
                <i class="fas fa-save" aria-hidden="true"></i>
                Save Draft
            </button>
        </form>
    </div>
</div>
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
        form.submission = {
            data: {!! $data !!},
        };

        form.on('change', function (submission) {
            document.getElementById('data').value = JSON.stringify(submission.data);
        });

        form.on('submit', function (submission) {
            // metadata may be useful (includes the timezone)
            document.getElementById('submission-stuff').innerHTML = 'Form data\n----------\n\n' + JSON.stringify(submission.data, null, 4);
            form.emit('submitDone', submission);
        });
    });
};
</script>
@endpush
