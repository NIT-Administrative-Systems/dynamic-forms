<?php

namespace Northwestern\SysDev\DynamicForms\Components\Layout;

use Northwestern\SysDev\DynamicForms\Components\BaseComponent;

class HtmlElement extends BaseComponent
{
    const TYPE = 'htmlelement';

    public function canValidate(): bool
    {
        return false;
    }
}
