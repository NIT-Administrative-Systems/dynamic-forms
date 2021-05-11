# Installation
Dynamic Forms for Laravel is available via composer.

## Prerequisites
You will need the following:

- PHP 8.0+
- Laravel 8+
- Bootstrap 4 
- FontAwesome 5
- An Amazon S3 bucket & access token, if you are going to handle file uploads via your dynamic forms

Dynamic Forms assumes that you are using [Laravel Mix](https://laravel.com/docs/8.x/mix) to prepare your JS/CSS assets. If you are not, you will need to transpile/minify the JavaScript that is installed into your `resources/js` folder using your own build system.

There is no Tailwind version at this time. This is driven by Formiojs' support for different CSS frameworks. Bootstrap 4, Bootstrap 3, and Semantic UI are the available options.

## Installation
Install the package, run the installation command, and build your frontend assets:

```bash
composer require northwestern-sysdev/dynamic-forms
php artisan dynamic-forms:install
yarn run prod
```

If you are going to use file uploads, you will want to ensure you have configured your Laravel app with a bucket name and credentials. If you are deploying to Laravel Vapor, no additional config is needed for file uploads.

Alternatively if you want to install for local file uploads
```bash
composer require northwestern-sysdev/dynamic-forms
php artisan dynamic-forms:install --local
yarn run prod
```

## Post-Installation Tasks
The installation command creates `App\Http\Controllers\DynamicFormsStorageController`. This controller is responsible for interacting with Amazon S3 to upload & download files.

Out of the box, this controller will deny all requests. You need to implement the `authorizeFileAction` method to check a gate or perform some other authorization check.

Depending on who will be uploading, you may also want to add the `auth` middleware to verify a user is logged in.
