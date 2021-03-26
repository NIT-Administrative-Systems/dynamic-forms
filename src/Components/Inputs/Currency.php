<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\RuleBag;

class Currency extends BaseComponent
{
    const TYPE = 'currency';

    public function processValidations(string $fieldKey, Factory $validator): MessageBag
    {
        $rules = new RuleBag($fieldKey, ['numeric']);

        $rules->addIfNotNull('required', $this->validation('required'));

        return $validator->make(
            [$fieldKey => $this->submissionValue()],
            $rules->rules(),
        )->messages();
    }

    /**
     * When there is a decimal, ensure it's truncated at two significant digits.
     */
    public function submissionValue(): mixed
    {
        return is_float($this->submissionValue)
            ? sprintf('%.2f', $this->submissionValue)
            : $this->submissionValue;
    }
}
