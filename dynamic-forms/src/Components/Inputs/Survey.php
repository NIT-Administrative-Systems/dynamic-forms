<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Rule;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;

class Survey extends BaseComponent
{
    const TYPE = 'survey';

    protected array $questions;
    protected array $validChoices;

    public function __construct(string $key, ?string $label, array $components, array $validations, bool $hasMultipleValues, array $additional)
    {
        parent::__construct($key, $label, $components, $validations, $hasMultipleValues, $additional);

        $this->questions = collect(Arr::get($this->additional, 'questions'))->map->value->all();
        $this->validChoices = collect(Arr::get($this->additional, 'values'))->map->value->all();
    }

    /**
     * Valid survey question keys.
     */
    public function questions(): array
    {
        return $this->questions;
    }

    /**
     * Valid survey answer keys.
     */
    public function validChoices(): array
    {
        return $this->validChoices;
    }

    /**
     * Get values, minus any invalid question fields.
     */
    public function submissionValue(): array
    {
        $cleaner = fn ($values) => collect($values)->only($this->questions())->all();

        return $this->hasMultipleValues()
            ? collect($this->submissionValue)->map($cleaner)->all()
            : $cleaner($this->submissionValue);
    }

    public function processValidations(string $fieldKey, mixed $submissionValue, Factory $validator): MessageBag
    {
        // This isn't a scalar, so our typical RuleBag pattern does not apply here.
        $rules = [];

        foreach ($this->questions() as $question) {
            $key = sprintf('%s.%s', $fieldKey, $question);

            $fieldRules = $this->validation('required')
                            ? ['string', 'required']
                            : ['nullable'];

            $fieldRules[] = Rule::in($this->validChoices());

            $rules[$key] = $fieldRules;
        }

        return $validator->make(
            [$fieldKey => $submissionValue],
            $rules
        )->messages();
    }
}
