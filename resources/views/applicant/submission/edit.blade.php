@extends('northwestern::purple-container')

@section('heading')
<h2>Apply for {{ $program->name }}</h2>
@endsection

@section('content')
<form id="submissionForm" method="post" action="{{ route('applicant.submission.update', ['submission' => $submission]) }}">
    @csrf
    @method('put')
    <input type="hidden" name="state">
    <input type="hidden" name="submissionValues">
</form>

@include('northwestern::errors')

<div id="formio-form"></div>
@endsection

@push('scripts')
<script lang="text/javascript">
window.onload = function() {
    Formio.createForm(document.getElementById('formio-form'), {!! $definition !!}).then(function (form) {
        form.submission = {
            data: {!! $data !!},
        };

        /*
        * It seems there is already some debounce applied to the Formio change events, but it will emit a change
        * at minimum every X ms. The draft save debounce should be fairly long to properly collapse those events
        * into one debounced event.
        *
        * Don't wanna DDoS our own server and all :P
        */
        var draftSaveDebounceMs = 1600;
        form.on('change', _.debounce(function (submission) {
            console.log('Firing draft save!');
            var draftEndpoint = "{{ route('applicant.submission.update', ['submission' => $submission]) }}";
            Formio.fetch(draftEndpoint, {
                method: 'PUT',
                body: JSON.stringify(submission.data),
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                options: { withCredentials: true },
            }).then(function (resp) {
                // @TODO: display this info somewhere!
                resp.json().then(function (json) { console.log(json) });
            }).catch(function (err) {
                console.log({message: 'error', err})
            });
        }, draftSaveDebounceMs));

        form.on('submit', function (submission) {
            var submitForm = document.getElementById('submissionForm');
            submitForm.querySelector('input[name=state]').value = submission.state;
            submitForm.querySelector('input[name=submissionValues]').value = JSON.stringify(submission.data);

            submitForm.submit();

            /**
             * Don't need this, since we don't want the AJAX-y spinners to finish before we leave the page.
             *
             * form.emit('submitDone', submission);
             */
        });
    });
};
</script>
@endpush
