<?php

namespace Northwestern\SysDev\DynamicForms\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class InstallResourceController extends GeneratorCommand
{
    protected $type = 'Controller';

    protected $signature = 'dynamic-forms:installResourceController';

    protected function getNameInput(): string
    {
        return 'DynamicFormsResourceController';
    }

    protected function getStub(): string
    {
        return __DIR__.'/../../../stubs/DynamicFormsResourceController.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Http\Controllers';
    }
}
