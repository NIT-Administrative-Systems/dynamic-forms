{{--
$definition string|null    Form definition
--}}
<script lang="text/javascript">
    window.onload = function () {
        {{-- Formio.setBaseUrl('{{ route('dynamic-forms.index') }}'); --}}
        new Formio.builder(
            document.getElementById('formio-builder'),
            @if(isset($definition)) {!! $definition !!} @else {} @endif,
            {
                builder: {
                    simpleFields: {
                        title: 'Basic',
                        default: true,
                        weight: 0,
                        components: {
                            textfield: true,
                            textarea: true,
                            number: true,
                            checkbox: true,
                            select: true,
                            selectboxes: true,
                            radio: true,
                            file: true,
                            button: true, // @TODO: MAYBE?
                        },
                    },
                    advancedFields: {
                        title: 'Widgets',
                        weight: 5,
                        components: {
                            url: true,
                            email: true,
                            phone: true,
                            address: true,
                            datetime: true,
                            day: true,
                            time: true,
                            currency: true,
                            survey: true,
                            signature: true,
                            nuDirectoryLookup: true,
                            /*
                            directorySearch: {
                                title: 'NU Directory',
                                key: 'directorySearch',
                                icon: 'graduation-cap',
                                schema: {
                                    label: 'NU Directory Search',
                                    type: 'select',
                                    key: 'nuDirectorySearch',
                                    widget: 'choicesjs',
                                    dataSrc: 'resource',
                                    input: true,
                                    // can lock widget type?
                                },
                            },
                            */
                        },
                    },
                    customLayout: {
                        title: 'Layout',
                        default: false,
                        weight: 10,
                        components: {
                            html: false,
                            content: true,
                            columns: true,
                            fieldset: true,
                            panel: true,
                            table: true,
                            well: true,
                        }
                    },
                    basic: false,
                    advanced: false,
                    layout: false,
                    data: false,
                    premium: false,
                }
            }
        ).then(function(builder) {
            document.getElementById('definition').value = JSON.stringify(builder.schema);

            builder.on('change', function (e) {
                document.getElementById('definition').value = JSON.stringify(builder.schema);
            })
        });
    };
</script>
