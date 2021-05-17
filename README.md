

# Dynamic Forms for Laravel [![PHPUnit Tests](https://github.com/NIT-Administrative-Systems/dynamic-forms/actions/workflows/phpunit.yml/badge.svg)](https://github.com/NIT-Administrative-Systems/dynamic-forms/actions/workflows/phpunit.yml) [![Coverage Status](https://coveralls.io/repos/github/NIT-Administrative-Systems/dynamic-forms/badge.svg?branch=develop)](https://coveralls.io/github/NIT-Administrative-Systems/dynamic-forms?branch=develop) [![Latest Stable Version](https://poser.pugx.org/northwestern-sysdev/dynamic-forms/v)](//packagist.org/packages/northwestern-sysdev/dynamic-forms) [![Total Downloads](https://poser.pugx.org/northwestern-sysdev/dynamic-forms/downloads)](//packagist.org/packages/northwestern-sysdev/dynamic-forms) 
User-defined forms are a perennial problem for developers. 

Dynamic Forms for Laravel gives you an easy solution: a drag-and-drop builder, an easy way to display the forms, and back-end validation. 

https://user-images.githubusercontent.com/29206313/118540354-20a85b80-b716-11eb-9140-b7760ef09a7e.mp4

## How does this work?
The front-end is powered by the open source [Form.io](https://github.com/formio/formio.js) JavaScript library. This is an awesome library: the builder is user-friendly, you can adjust what's offered, and add your own custom form fields. 

On the backend, it's as simple as calling `$request->validateDynamicForm()`. It behaves just like the [`validate`](https://laravel.com/docs/8.x/validation#quick-writing-the-validation-logic) method you're used to in Laravel. 

You **do not** need to use the Form.io SaaS platform. Your Laravel application is filling that role.

## Getting Started
```
composer require northwestern-sysdev/dynamic-forms
php artisan dynamic-forms:install
yarn install
yarn run prod
```

Further information about usage can be [found in the docs](https://nit-administrative-systems.github.io/dynamic-forms/).

## Contributing
If you'd like to contribute to the library, you are welcome to submit a pull request!

There is [a roadmap](https://github.com/NIT-Administrative-Systems/dynamic-forms/projects/1) under the projects tab, so if you're looking for ideas, those issues are a great place to start.
