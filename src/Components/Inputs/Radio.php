<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Rule;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\RuleBag;

class Radio extends BaseComponent
{
    const TYPE = 'radio';

    protected array $radioChoices;

    public function __construct(
        string $key,
        ?string $label,
        ?string $errorLabel,
        array $components,
        array $validations,
        bool $hasMultipleValues,
        ?array $conditional,
        ?string $customConditional,
        string $case,
        null|array|string $calculateValue,
        mixed $defaultValue,
        array $additional
    ) {
        parent::__construct($key, $label, $errorLabel, $components, $validations, $hasMultipleValues, $conditional, $customConditional, $case, $calculateValue, $defaultValue, $additional);

        $this->radioChoices = collect(Arr::get($this->additional, 'values'))
            ->map(function (array $pair) {
                return trim(Arr::get($pair, 'value'));
            })
            ->all();
    }

    /**
     * {@inheritDoc}
     */
    public function submissionValue(): mixed
    {
        $value = parent::submissionValue();

        if ($this->hasMultipleValues()) {
            $value ??= [];

            foreach ($value as $i => $singleValue) {
                $value[$i] = (string) $singleValue;
            }

            return $value;
        }

        return is_scalar($value) ? (string) $value : $value;
    }

    /**
     * Get valid radio options.
     */
    public function radioChoices(): array
    {
        return $this->radioChoices;
    }

    public function processValidations(string $fieldKey, string $fieldLabel, mixed $submissionValue, Factory $validator): MessageBag
    {
        $rules = new RuleBag($fieldKey, ['string']);

        // Nullable if this isn't required so Rule::in doesn't fail it
        if ($this->validation('required')) {
            $rules->add('required');
        } else {
            $rules->add('nullable');
        }

        $rules->add(Rule::in($this->radioChoices()));

        return $validator->make(
            [$fieldKey => $submissionValue],
            $rules->rules(),
            [],
            [$fieldKey => $fieldLabel]
        )->messages();
    }
}
