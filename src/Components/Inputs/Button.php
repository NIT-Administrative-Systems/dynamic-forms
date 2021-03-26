<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\BaseComponent;

class Button extends BaseComponent
{
    const TYPE = 'button';

    public function canValidate(): bool
    {
        return false;
    }
}
