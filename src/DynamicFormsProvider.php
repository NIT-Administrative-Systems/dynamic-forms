<?php

namespace Northwestern\SysDev\DynamicForms;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Northwestern\SysDev\DirectoryLookupComponent\DirectoryLookup;
use Northwestern\SysDev\DynamicForms\Forms\Form;

class DynamicFormsProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ComponentRegistry::class, function ($app) {
            return new ComponentRegistry;
        });
    }

    public function boot()
    {
        // @TODO move me

        /** @var ComponentRegistry $registry */
        $registry = $this->app->make(ComponentRegistry::class);
        $registry->register(DirectoryLookup::class);

        Request::macro('validateDynamicForm', function (string $definitionJson, string $submissionJson) use ($registry) {
            $formDefinition = new Form($definitionJson); // @TODO pass registry
            $validator = $formDefinition->validate($submissionJson);

            if (! $validator->isValid()) {
                throw (new ValidationException($validator))
                    ->redirectTo($this->session()->previousUrl());
            }

            return $validator->values();
        });
    }
}
