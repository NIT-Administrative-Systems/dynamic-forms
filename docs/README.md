# Dynamic Forms for Laravel
User-defined forms are a perennial problem for developers.

Dynamic Forms for Laravel gives you an easy solution: a drag-and-drop builder, an easy way to display the forms, and back-end validation to ensure the submitted data is good.

If you want to see it in action, here are some demo videos:

::: details Creating a form in the builder
<video controls="controls" preload="none" width="100%">
    <source src="./assets/builder_demo.webm" type="video/webm">
</video>
:::

::: details Filling out a dynamic form
<video controls="controls" preload="none" width="100%">
<source src="./assets/form_demo.webm" type="video/webm">
</video>
:::

## How does this work?
The front-end is powered by the open source [Form.io](https://github.com/formio/formio.js) JavaScript library. This is an awesome library: the builder is user-friendly, you can adjust what's offered, and add your own custom form fields.

On the backend, it's as simple as calling `$request->validateDynamicForm()`. It behaves just like the [`validate`](https://laravel.com/docs/8.x/validation#quick-writing-the-validation-logic) method you're used to in Laravel, giving you valid data you can trust.

You **do not** need to use the Form.io SaaS platform. Your Laravel application is filling that role.

## Concepts
There are a couple pieces to be aware of:

- The **form builder** is a WYSIWYG editor that people use to create forms. 
- **Form definitions** are created & updated by the form builder. Definitions are JSON documents that describe a form's fields, validation logic, help text, etc.
- **Components** are individual form fields. Formiojs comes with a wide variety of components, from basic `<input type='text>` fields to rich WYSIWYG textareas and API-backed address lookups. You can reconfigure existing components to create new ones, or write your own from scratch.
- **Forms** are shown when you render a form definition. The form can be read-write or read-only (to display a submitted form). Forms produce submissions in the form of key:value JSON documents.

## Supported Features
Formiojs offers a lot of functionality. Dynamic Forms for Laravel has implemented a limited subset of all its available features.

Most of the decisions not to include something were driven by what would give us a good minimum viable product. If there are missing features that you would like to see, please feel free to submit an issue to discuss including it.

### Components
Most of the Formiojs components are supported in some configuration. These components have limitations:

- Address only supports Open Street Maps
- File only supports Amazon S3 and local storage (base64, dropbox, azure, and indexeddb support can be [added](extending.md#adding-storage-backends))
- Select only supports values, and not API-backed resources

These components are not supported at all:
    
- HTML Element
- Tags
- Hidden
- Data Map
- Data Grid
- Edit Grid
- Tree
- Tabs
- ReCAPTCHA
- Resource
- Nested Form

### Scripting
Formiojs offers several methods for creating dependencies between form fields: simple UI-driven setups, JSON Logic, and custom JavaScript.

Dynamic Forms for Laravel only supports the simple UI-driven dependencies.

No JS eval or other scripting is supported at this point in time.

### Other Features
PDF forms and form wizards are not supported.
