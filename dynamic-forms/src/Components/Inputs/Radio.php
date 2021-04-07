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
        array $components,
        array $validations,
        bool $hasMultipleValues,
        ?array $conditional,
        ?string $customConditional,
        array $additional
    ) {
        parent::__construct($key, $label, $components, $validations, $hasMultipleValues, $conditional, $customConditional, $additional);

        $this->radioChoices = collect(Arr::get($this->additional, 'values'))->map->value->all();
    }

    /**
     * Get valid radio options.
     */
    public function radioChoices(): array
    {
        return $this->radioChoices;
    }

    public function processValidations(string $fieldKey, mixed $submissionValue, Factory $validator): MessageBag
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
        )->messages();
    }
}
