<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag as MessageBagImpl;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;

class SelectBoxes extends BaseComponent
{
    const TYPE = 'selectboxes';

    protected array $options;

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

        $this->options = collect($this->additional['values'])
            ->map(function (array $pair) {
                return trim(Arr::get($pair, 'value'));
            })
            ->all();
    }

    public function getOptionValues(): array
    {
        return $this->options;
    }

    /**
     * Gets the submission value.
     *
     * Any invalid options included in the submission will be excluded.
     *
     * @return bool[]
     */
    public function submissionValue(): array
    {
        $cleaner = fn ($boxes) => collect($boxes)->only($this->getOptionValues())->all();

        return $this->hasMultipleValues()
            ? collect($this->submissionValue)->map($cleaner)->all()
            : $cleaner($this->submissionValue);
    }

    /**
     * Run validations.
     *
     * The typical RuleBag pattern isn't very useful here -- we're not operating on a scalar,
     * and Illuminate's validator expects to be doing so.
     * @param string $fieldKey
     * @param mixed $submissionValue
     * @param Factory $validator
     * @return MessageBag
     */
    protected function processValidations(string $fieldKey, string $fieldLabel, mixed $submissionValue, Factory $validator): MessageBag
    {
        $bag = new MessageBagImpl;

        $checkedNum = collect($submissionValue)->sum(fn ($value) => $value ? 1 : 0);
        $minSelected = $this->validation('minSelectedCount');
        $maxSelected = $this->validation('maxSelectedCount');

        // Required checkbox is just shorthand for setting the minimum to one
        if ($this->validation('required') && ! $minSelected) {
            $minSelected = 1;
        }

        if ($minSelected !== null && $checkedNum < $minSelected) {
            $message = sprintf('You must select at least %s items.', $minSelected);
            $bag->add($fieldLabel, Arr::get($this->additional, 'minSelectedCountMessage', $message));
        }

        if ($maxSelected !== null && $checkedNum > $maxSelected) {
            $message = sprintf('You cannot select more than %s items.', $minSelected);
            $bag->add($fieldLabel, Arr::get($this->additional, 'maxSelectedCountMessage', $message));
        }

        return $bag;
    }
}
