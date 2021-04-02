<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\RuleBag;

class Number extends BaseComponent
{
    const TYPE = 'number';

    protected function processValidations(string $fieldKey, mixed $submissionValue, Factory $validator): MessageBag
    {
        $fieldKey = $this->label() ?? $this->key();

        $rules = new RuleBag($fieldKey, ['numeric']);
        $rules->addIf('required', $this->validation('required') === true);
        $rules->addIfNotNull(sprintf('min:%s', $this->validation('min')), $this->validation('min'));
        $rules->addIfNotNull(sprintf('max:%s', $this->validation('max')), $this->validation('max'));

        $validator = app()->make('validator')->make(
            [$fieldKey => $submissionValue],
            $rules->rules(),
        );

        return $validator->messages();
    }
}
