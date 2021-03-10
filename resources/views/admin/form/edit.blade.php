@extends('northwestern::purple-container')

@section('heading')
<h2>Edit {{ $program->name }} &mdash; {{ $form->type->name }}</h2>
@endsection

@section('content')
<div id="formio-builder"></div>
@endsection

@push('scripts')
<script lang="text/javascript">
    window.onload = function() {
        Formio.icons = 'fontawesome';
        new Formio.builder(
            document.getElementById('formio-builder'),
            {!! $definition !!},
            {
                builder: {
                    // @TODO
                }
            }
        ).then(function(builder) {
            builder.on('change', function (e) {
                console.log(JSON.stringify(builder.schema));
            })
        });

/*
        console.log(builder);
        builder.instance.ready.then(function() {
            console.log('the builder is ready');
        });
*/

        /*
        .then(function(builder) {
            builder.on('saveComponent', function() {
                console.log('sup');
                var form_definition = JSON.stringify(builder.schema);

                console.log('Something happened. Guess we ought to save the form definition:');
                console.log(form_definition);

                $.post('/setup-form', {
                    formio_definition: form_definition
                }).done(function () {
                    console.log('OK, saved the definition!')
                });
            });
        });
        */
    };
</script>
@endpush
