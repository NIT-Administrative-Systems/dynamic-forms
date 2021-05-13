# Using Dynamic Forms


## Builder
The Formiojs builder will create a JSON document containing all of the configuration the user has made for their dynamic form.

In the below example, whenever anything about the form configuration is added or updated, the JSON will be written to a normal HTML form's `<input type='hidden'>`. This form definition will be submitted to Laravel when the user hits the submit button on the form. Laravel can process this like any typical form submission.

```php
// App\Http\Controllers\BuilderController

class BuilderController extends Controller
{
    public function create()
    {
        return view('builder')->with([
            // can provide a previously-saved form definition here
            'definition' => null, 
        ]);
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'definition' => 'required|json'
        ]);
        
        // You can then save this to your DB, a file,
        // or whatever you're using for persistence.
        dump($data['definition']);
    }
}

```

```html
<!-- builder.blade.php -->

<div class="row">
    <div class="col-md-6">
        <h1>Form Builder Demo</h1>
    </div>
    
    <div class="col-md-6 text-right">
        <!-- This form holds the JSON form definition. -->
        <form method="post" action="{{ route('form-builder.store') }}">
            @csrf
            <input type="hidden" name="definition" id="definition" value="">

            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-save" aria-hidden="true"></i>
                Save Form
            </button>        
    </div>
</div>

<!-- This becomes the builder. -->
<div id="formio-builder"></div>

<!-- The options can be customized to control the available elements. -->
<script lang="text/javascript">
    window.onload = function () {
        new Formio.builder(
            document.getElementById('formio-builder'),
            @if(isset($definition)) {!! $definition !!} @else {} @endif,
            {} // these are the opts you can customize
        ).then(function(builder) {
            // Exports the JSON representation of the dynamic form to that form we defined above
            document.getElementById('definition').value = JSON.stringify(builder.schema);
            
            builder.on('change', function (e) {
                // On change, update the above form w/ the latest dynamic form JSON
                document.getElementById('definition').value = JSON.stringify(builder.schema);
            })
        });;
    };
</script>
```

### Customizing the Components Menu
Dynamic Forms comes with a set of defaults for the builder's sidebar, which contains the library of available components. This default disables unsupported components and organizes them into better groupings.

You can review and adjust these defaults in the `resources/js/formio/builder-sidebar.js` file.

If you need to customize an individual builder, you can do so by passing the `builder` object in the options param. Doing so gives you complete control over the sidebar. For more details on how this config option works, see the [Formiojs example](https://formio.github.io/formio.js/app/examples/custombuilder.html). 

```html
<script lang="text/javascript">
    /**
     * The defaults from builder-sidebar.js are published 
     * as window.DynamicFormsBuilderSidebar.
     * 
     * You can adjust this object, or make a similar object yourself:
     */
    console.log(DynamicFormsBuilderSidebar);
    
    window.onload = function () {
        new Formio.builder(
            document.getElementById('formio-builder'),
            @if(isset($definition)) {!! $definition !!} @else {} @endif,
            {
                builder: DynamicFormsBuilderSidebar,
            },
        );
    };
</script>
```

## Form
The Formiojs library will render a form definition from the builder, so users can fill out your forms.

In the below example, the user will click on a Submit or Save Draft button that have been added to the form. Clicking either of those buttons will cause the JSON and submission type to be copied over to a regular HTML form, which is then submitted to Laravel.

Laravel validates the form using the `$request->validateDynamicForm($definition, $jsonString)` method. This behaves very similar to the `$request->validate()` method: if errors are found, the user is redirected back to the form with the `$errors` bag populated.

If no validation errors are found, you will receive data for each form field in the form definition. Any extraneous fields that do not exist in the form definition are stripped out, leaving you with safe data that can be stored in your backend of choice. 

```php
// App\Http\Controllers\FormController.php

class FormController extends Controller
{
    public function create()
    {
        return view('form')->with([
            'definition' => FormDefinition::find(1), // get some definition JSON
            'data' => '{}', // you can edit a form by providing the old data 
        ]);
    }
    
    public function store(Request $request)
    {
        if ($request->get('state') === 'draft') {
            // Someone added a 'Save Draft' button to the form, and the user clicked that.
            // You can do some different behaviours if you'd like.
        }
    
        $data = $request->validateDynamicForm(
            FormDefinition::find(1), // get some definition JSON
            $request->get('submissionValues')
        );
        
        // Here is your validated form data
        dd($data);
    }
}
```

```html
<!-- form.blade.php -->

<div class="row">
    <div class="col-md-6">
        <h1>Form Builder Demo</h1>
    </div>
    
    <div class="col-md-6 text-right">
        <!-- This form holds the values the user has entered, as a JSON document. -->
        <form method="post" action="{{ route('form.store') }}">
            @csrf
            
            <!-- State can be used to capture a Submit vs. Save Draft button -->
            <input type="hidden" name="state">
            
            <!-- The JSON with all the values -->
            <input type="hidden" name="submissionValues" id="submissionValues" value="">
    </div>
</div>

<!-- Any server-side errors will be shown here. This is a fallback for when the client-side validations miss something. -->
@if ($errors->any())
<div class="alert alert-danger">
    <p style="font-size: 16pt"><strong>Oops</strong>, there was an issue with that.</p>
    <ul class="ml-5">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- This becomes the builder. -->
<div id="formio-form"></div>

<script lang="text/javascript">
    window.onload = function() {
        Formio.createForm(document.getElementById('formio-form'), {!! $definition !!}).then(function (form) {
            form.submission = {
                data: {!! $data !!},
            };
            
            form.on('submit', function (submission) {
                var submitForm = document.getElementById('submissionForm');
                submitForm.querySelector('input[name=state]').value = submission.state;
                submitForm.querySelector('input[name=submissionValues]').value = JSON.stringify(submission.data);
                
                submitForm.submit();
            });
        });
    };
</script>
```

## Showing a Submitted Form

```php
// App\Http\Controllers\FormController.php

class FormController extends Controller
{
    public function show()
    {
        return view('show')->with([
            'definition' => FormDefinition::find(1), // get some definition JSON
            'data' => FormSubmission::find(1), // get some submission JSON
        ]);
    }
}
```

```html
<!-- show.blade.php -->

<!-- This becomes the form. -->
<div id="formio-form"></div>

<script lang="text/javascript">
    window.onload = function() {
        // The third param's readOnly flag turns off buttons & marks all fields as readonly.
        Formio.createForm(document.getElementById('formio-form'), {!! $definition !!}, {readOnly: true}).then(function (form) {
            form.submission = {
                data: {!! $data !!},
        };
    };
</script>
```

## Advanced Usage
The examples above all rely on the user clicking a submit button. The code involved in this is in the examples -- a Formiojs event is bound to a callback, which is manipulating an HTML form. 

If you wanted, you could replace this code with AJAX requests. This could give your forms auto-save functionality.

## Definition Changes
Depending on how you intend to use Formio, your application may need to be aware of form definitions being altered. Fields being added, removed, or modified potentially invalidates earlier form submissions made for the previous version of the definition.

If your application is going to permit form definitions to change once users have started submitting forms, you will need to track the version history and associate submissions with a specific version of a form definition.
