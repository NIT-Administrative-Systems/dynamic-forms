<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\RuleBag;

class PhoneNumber extends BaseComponent
{
    const TYPE = 'phoneNumber';

    protected function processValidations(string $fieldKey, Factory $validator): MessageBag
    {
        $rules = new RuleBag($fieldKey, ['string']);

        $rules->add('string');
        $rules->addIfNotNull('required', $this->validation('required'));

        return $validator->make(
            [$fieldKey => $this->submissionValue()],
            $rules->rules(),
        )->messages();
    }
}
