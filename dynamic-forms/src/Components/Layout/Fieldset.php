<?php

namespace Northwestern\SysDev\DynamicForms\Components\Layout;

use Northwestern\SysDev\DynamicForms\Components\BaseComponent;

class Fieldset extends BaseComponent
{
    const TYPE = 'fieldset';

    public function canValidate(): bool
    {
        return false;
    }
}
