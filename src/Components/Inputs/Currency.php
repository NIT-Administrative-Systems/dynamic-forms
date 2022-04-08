<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\RuleBag;

class Currency extends BaseComponent
{
    const TYPE = 'currency';

    public function processValidations(string $fieldKey, string $fieldLabel, mixed $submissionValue, Factory $validator): MessageBag
    {
        $rules = new RuleBag($fieldKey, ['numeric']);

        $rules->addIfNotNull('required', $this->validation('required'));

        return $validator->make(
            [$fieldKey => $submissionValue],
            $rules->rules(),
            [],
            [$fieldKey => $fieldLabel]
        )->messages();
    }

    /**
     * When there is a decimal, ensure it's truncated at two significant digits.
     */
    public function submissionValue(): mixed
    {
        $cleaner = fn ($num) => is_float($num) ? sprintf('%.2f', $num) : $num;

        return $this->hasMultipleValues()
            ? collect($this->submissionValue)->map($cleaner)->all()
            : $cleaner($this->submissionValue);
    }
}
