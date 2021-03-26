<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\RuleBag;

class Checkbox extends BaseComponent
{
    const TYPE = 'checkbox';

    protected function processValidations(string $fieldKey, Factory $validator): MessageBag
    {
        $rules = new RuleBag($fieldKey, ['boolean']);
        $rules->addIf('accepted', $this->validation('required') === true);

        return $validator->make(
            [$fieldKey => $this->submissionValue()],
            $rules->rules(),
        )->messages();
    }
}
