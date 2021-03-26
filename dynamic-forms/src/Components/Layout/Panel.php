<?php

namespace Northwestern\SysDev\DynamicForms\Components\Layout;

use Northwestern\SysDev\DynamicForms\Components\BaseComponent;

class Panel extends BaseComponent
{
    const TYPE = 'panel';

    public function canValidate(): bool
    {
        return false;
    }
}
