<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\RuleBag;

class Number extends BaseComponent
{
    const TYPE = 'number';

    protected function processValidations(string $fieldKey, string $fieldLabel, mixed $submissionValue, Factory $validator): MessageBag
    {
        $rules = new RuleBag($fieldKey, ['numeric']);

        $this->validation('required')
            ? $rules->add('required')
            : $rules->add('nullable');

        $rules->addIfNotNull(sprintf('min:%s', $this->validation('min')), $this->validation('min'));
        $rules->addIfNotNull(sprintf('max:%s', $this->validation('max')), $this->validation('max'));

        return $validator->make(
            [$fieldKey => $submissionValue],
            $rules->rules(),
            [],
            [$fieldKey => $fieldLabel]
        )->messages();
    }

    /**
     * Ensure we return numerics instead of strings.
     */
    public function submissionValue(): mixed
    {
        $requireFloat = Arr::get($this->additional, 'requireDecimal');
        $significantDigits = Arr::get($this->additional, 'decimalLimit');

        $caster = function ($number) use ($requireFloat, $significantDigits) {
            if ($number === null || $number === '') {
                return null;
            }

            if ($requireFloat) {
                // Explicitly configured to be a float
                $number = (float) $number;
            } else {
                // Not configured to be a float, so go with whatever they entered.
                $number = is_float($number)
                    ? (float) $number
                    : (int) $number;
            }

            if ($significantDigits) {
                $number = (float) sprintf("%.{$significantDigits}f", $number);
            }

            return $number;
        };

        return $this->hasMultipleValues()
            ? collect($this->submissionValue)->map($caster)->all()
            : $caster($this->submissionValue);
    }
}
