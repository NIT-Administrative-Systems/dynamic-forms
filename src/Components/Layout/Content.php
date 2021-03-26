<?php

namespace Northwestern\SysDev\DynamicForms\Components\Layout;

use Northwestern\SysDev\DynamicForms\Components\BaseComponent;

class Content extends BaseComponent
{
    const TYPE = 'content';

    public function canValidate(): bool
    {
        return false;
    }
}
