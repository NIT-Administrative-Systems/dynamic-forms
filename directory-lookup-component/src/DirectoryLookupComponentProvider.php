<?php

namespace Northwestern\SysDev\DirectoryLookupComponent;

use Illuminate\Support\ServiceProvider;
use Northwestern\SysDev\DynamicForms\ComponentRegistry;

class DirectoryLookupComponentProvider extends ServiceProvider
{
    public function boot()
    {
        /** @var ComponentRegistry $registry */
        $registry = $this->app->make(ComponentRegistry::class);

        $registry->register(DirectoryLookup::class);
    }
}
