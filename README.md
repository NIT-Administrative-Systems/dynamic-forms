# ADO Competitive Applications [![Dev Deployment](https://github.com/NIT-Administrative-Systems/competitive-applications/actions/workflows/deploy.yml/badge.svg)](https://github.com/NIT-Administrative-Systems/competitive-applications/actions/workflows/deploy.yml)
The Competitive Applications system is a workflow system for running contests: people apply to receive a finite resource, their applications are rated, and the best applications win.

In practice, this is used for undergraduate research grants.

## Running the App
For development, you will need Homestead, Valet, or similar.

(*This might work with Laravel Sail, but it hasn't been tried.*)

```sh
$ composer install
$ cp .env.example .env
$ vi .env # or your favorite text editor
$ php artisan migrate:fresh --seed
$ php artisan db:seed --class=DemoSeeder # or TestDataSeeder, for a large fully-random dataset
$ yarn install
$ yarn run prod
```

You can use `yarn watch` if you are editing the JS/SCSS assets.

### Developing w/ Local Copies of [Dynamic Forms for Laravel](#)
The [Dynamic Forms for Laravel](#) package was created alongside this app. If you need to do development against a copy instead of installing from the composer registry:

```
right now it just lives in dynamic-forms/ 
and directory-lookup-component/ so edit files 
there directly.

but once it's split off, instructions will be here!
```

## Autocomplete / Laravel Type Hints
The `laravel-ide-helper` package will automatically dump framework type-hints for fluent DB builder methods and other framework stuff with `mixed` or unspecified return types whenever `composer update` is run. If you need to run this manually, check out the `post-update-cmd` hook in `composer.json`.

Similar IDE helper data can be generated for Eloquent attributes. Once your database has been set up and migrated:

```sh
$ php artisan ide-helper:models -N
```

This data is used by an IDE that supports `_ide_helper*.php` files and/or `.phpstorm.meta.php` files. 

VSCode with PHP Intelliphense works, as does PHPStorm.
