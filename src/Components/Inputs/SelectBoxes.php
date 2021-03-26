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

    public function __construct(string $key, ?string $label, array $components, array $validations, array $additional)
    {
        parent::__construct($key, $label, $components, $validations, $additional);

        $this->options = collect($this->additional['values'])->map->value->all();
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
        return collect($this->submissionValue)
            ->only($this->getOptionValues())
            ->all();
    }

    /**
     * Run validations.
     *
     * The typical RuleBag pattern isn't very useful here -- we're not operating on a scalar,
     * and Illuminate's validator expects to be doing so.
     */
    protected function processValidations(string $fieldKey, Factory $validator): MessageBag
    {
        $bag = new MessageBagImpl;

        $checkedNum = collect($this->submissionValue())->sum(fn ($value) => $value ? 1 : 0);
        $minSelected = $this->validation('minSelectedCount');
        $maxSelected = $this->validation('maxSelectedCount');

        // Required checkbox is just shorthand for setting the minimum to one
        if ($this->validation('required') && ! $minSelected) {
            $minSelected = 1;
        }

        if ($minSelected !== null && $checkedNum < $minSelected) {
            $message = sprintf('You must select at least %s items.', $minSelected);
            $bag->add($fieldKey, Arr::get($this->additional, 'minSelectedCountMessage', $message));
        }

        if ($maxSelected !== null && $checkedNum > $maxSelected) {
            $message = sprintf('You cannot select more than %s items.', $minSelected);
            $bag->add($fieldKey, Arr::get($this->additional, 'maxSelectedCountMessage', $message));
        }

        return $bag;
    }
}
