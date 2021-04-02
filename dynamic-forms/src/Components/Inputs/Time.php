<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\RuleBag;
use Northwestern\SysDev\DynamicForms\Rules\TimeFormat;

class Time extends BaseComponent
{
    const TYPE = 'time';

    public function processValidations(string $fieldKey, mixed $submissionValue, Factory $validator): MessageBag
    {
        $rules = new RuleBag($fieldKey, ['string']);

        $rules->addIfNotNull('required', $this->validation('required'));
        $rules->add(new TimeFormat);

        return $validator->make(
            [$fieldKey => $submissionValue],
            $rules->rules(),
        )->messages();
    }
}
