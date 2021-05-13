<?php

namespace Northwestern\SysDev\DynamicForms\Forms;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag as MessageBagImpl;
use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;

class ValidatedForm implements Validator
{
    protected Collection $values;
    protected MessageBag $messages;

    public function __construct(array $flatComponents, array $values)
    {
        $messageBag = new MessageBagImpl;
        $transformedValues = collect();
        $components = $this->validatableComponents($flatComponents, $values);

        foreach ($components as $component) {
            $messageBag->merge($component->validate());
            $transformedValues->put($component->key(), $component->submissionValue());
        }

        // If any components are unknown or were removed by conditional logic, discard the corresponding value
        $this->values = $transformedValues->only(array_keys($components));
        $this->messages = $messageBag;
    }

    /**
     * Gets validation failure messages.
     */
    public function messages(): MessageBag
    {
        return $this->messages;
    }

    /**
     * Whether or not the submission is valid.
     */
    public function isValid(): bool
    {
        return $this->messages()->isEmpty();
    }

    /**
     * Returns the cleaned & transformed form submission data.
     *
     * Any keys submitted for components that are either unknown or
     * hidden will be stripped out, similar to how $request->validate()
     * will only return fields that have been explicitly given rules.
     */
    public function values(): array
    {
        return $this->values->all();
    }

    /**
     * Returns the list of File objects in a validated request.
     */
    public function allFiles(): array
    {
        $list = [];
        foreach ($this->values as $key => $file) {
            if (str_starts_with($key, 'file') && isset($file[0])) {
                $list[] = $file[0];
            }
        }

        return $list;
    }

    /**
     * Return a flat list of components that should be validated.
     *
     * Conditional logic that excludes fields will be evaluated here
     * and may remove components from consideration.
     *
     * @return ComponentInterface[]
     */
    protected function validatableComponents(array $flatComponents, array $values): array
    {
        $components = collect($flatComponents)->filter->canValidate();

        // Populate the components with their data so we can evaluate conditionals
        $data = collect($values)->only($components->keys());
        $data->each(fn ($value, $key) => $components[$key]->setSubmissionValue($value));

        $componentsWithConditionals = $components->filter->hasConditional();

        /** @var ComponentInterface $component */
        foreach ($componentsWithConditionals as $component) {
            $condition = $component->conditional();

            if (! $condition($data->all())) {
                $components->forget($component->key());
            }
        }

        return $components->all();
    }

    /**
     * @internal
     */
    public function getMessageBag()
    {
        return $this->messages();
    }

    /**
     * @internal
     */
    public function validate()
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @internal
     */
    public function validated()
    {
        return $this->values();
    }

    /**
     * @internal
     */
    public function fails()
    {
        return ! $this->isValid();
    }

    /**
     * @internal
     */
    public function failed()
    {
        return $this->messages->keys();
    }

    /**
     * @internal
     */
    public function sometimes($attribute, $rules, callable $callback)
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @internal
     */
    public function after($callback)
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @internal
     */
    public function errors()
    {
        return $this->messages();
    }
}