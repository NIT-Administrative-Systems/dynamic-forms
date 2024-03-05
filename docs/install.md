# Installation
Dynamic Forms for Laravel is available via composer.

## Prerequisites
You will need the following:

- PHP 8.0+
- Laravel 8+
- Bootstrap 4 
- FontAwesome 5
- (optional) An Amazon S3 bucket & access token, if you are going to handle file uploads via S3 for your dynamic forms

Dynamic Forms assumes that you are using [Laravel Vite](https://laravel.com/docs/10.x/vite) or [Laravel Mix](https://laravel.com/docs/8.x/mix) to prepare your JS/CSS assets. If you are not, you will need to transpile/minify the JavaScript that is installed into your `resources/js` folder using your own build system.

There is no Tailwind or Bootstrap 5 version at this time. This is driven by Formiojs' support for different CSS frameworks. Bootstrap 4, Bootstrap 3, and Semantic UI are the available options.

## Installation
Install the package, run the installation command, and build your frontend assets:

```bash
composer require northwestern-sysdev/dynamic-forms
php artisan dynamic-forms:install
yarn install
yarn run prod
```

If you are going to use S3 for file uploads, you will want to ensure you have configured your Laravel app with a bucket name and credentials. If you are deploying to Laravel Vapor, no additional config is needed for file uploads.

## Post-Installation Tasks

### Storage
The installation command creates `App\Http\Controllers\DynamicFormsStorageController`. This controller is responsible for interactions from the form to backend storage providers such as Amazon S3 to upload & download files.

Out of the box, this controller will deny all requests. You need to implement the `authorizeFileAction` method to check a gate or perform some other authorization check.

Depending on who will be uploading, you may also want to add the `auth` middleware to verify a user is logged in.

For file uploads, S3 and direct server uploads are both options available in the builder. You can set the env variable `MIX_STORAGE_DEFAULT_VALUE` to `s3` or `url` if you do not need to give people a choice.

### Resources
The installation command creates `App\Http\Controllers\DynamicFormsResourceController`. This controller is responsible for handling Resource Requests for Select components that utilize the Resource Source.

This controller presents Resources for any php files in `App\Http\Controllers\Resources` that implements ResourceInterface.

Request headers are made available through the `$context` parameter in `ResourceInterface::submissions` if additional information is required to fetch the resources. To include information in the request header, a [Formio `preRequest` plugin hook](https://help.form.io/developers/fetch-plugin-api#prerequest-requestargs) can be configured. Provided below is an example of what that may look like.
```javascript
Formio.registerPlugin({
    preRequest: (requestArgs) => {
        const exampleElement = document.querySelector('#exampleElement');
        if (exampleElement) {
            requestArgs.opts.header.set('X-Foo-Bar', exampleElement.dataset.fooBar);
        }
    }
}, 'exampleContext');
```
