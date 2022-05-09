<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\MessageBag as MessageBagImpl;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;

class Hidden extends BaseComponent
{
    const TYPE = 'hidden';

    protected function processValidations(string $fieldKey, string $fieldLabel, mixed $submissionValue, Factory $validator): MessageBag
    {
        // This component supports no validations in the builder, so there is nothing to check.
        return new MessageBagImpl;
    }
}
