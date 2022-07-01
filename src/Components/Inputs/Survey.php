<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Rule;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;

class Survey extends BaseComponent
{
    const TYPE = 'survey';

    protected array $questions;
    protected array $validChoices;

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

    /**
     * @internal This will slugify the question keys, since the Formio builder will let you enter the data_get()'s special
     *           characters (* and .), which will break validation.
     */
    public function processValidations(string $fieldKey, string $fieldLabel, mixed $submissionValue, Factory $validator): MessageBag
    {
        // This isn't a scalar, so our typical RuleBag pattern does not apply here.
        $rules = [];

        foreach ($this->questions() as $question) {
            $key = sprintf('%s.%s', $fieldKey, Str::slug($question));

            $fieldRules = $this->validation('required')
                            ? ['string', 'required']
                            : ['nullable'];

            $fieldRules[] = Rule::in($this->validChoices());

            $rules[$key] = $fieldRules;
        }

        $submissionValue = collect($submissionValue)
            ->mapWithKeys(fn (mixed $value, mixed $key) => [Str::slug($key) => $value])
            ->all();

        return $validator->make(
            [$fieldKey => $submissionValue],
            $rules,
            [],
            [$fieldKey => $fieldLabel]
        )->messages();
    }
}
