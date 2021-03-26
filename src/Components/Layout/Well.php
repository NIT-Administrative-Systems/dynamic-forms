<?php

namespace Northwestern\SysDev\DynamicForms\Components\Layout;

use Northwestern\SysDev\DynamicForms\Components\BaseComponent;

class Well extends BaseComponent
{
    const TYPE = 'well';

    public function canValidate(): bool
    {
        return false;
    }
}
