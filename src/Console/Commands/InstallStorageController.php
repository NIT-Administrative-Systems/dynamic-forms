<?php

namespace Northwestern\SysDev\DynamicForms\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class InstallStorageController extends GeneratorCommand
{
    protected $type = 'Controller';

    protected $signature = 'dynamic-forms:installStorageController';

    protected function getNameInput(): string
    {
        return 'DynamicFormsStorageController';
    }

    protected function getStub(): string
    {
        return __DIR__.'/../../../stubs/DynamicFormsStorageController.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Http\Controllers';
    }
}
