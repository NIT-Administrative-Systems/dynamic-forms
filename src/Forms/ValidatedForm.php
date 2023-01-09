<?php

namespace Northwestern\SysDev\DynamicForms\Forms;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag as MessageBagImpl;
use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Northwestern\SysDev\DynamicForms\FileComponentRegistry;
use Northwestern\SysDev\DynamicForms\Forms\Concerns\HandlesTree;

class ValidatedForm implements Validator
{
    use HandlesTree;

    protected array $components;
    protected array $flatComponents;
    protected array $forgetComponentKeys = [];

    protected Collection $values;
    protected MessageBag $messages;

    public function __construct(array $components, array $values)
    {
        $this->components = $components;
        $messageBag = new MessageBagImpl;
        $transformedValues = collect();

        $this->populateComponentTreeWithData($this->components, $values);
        $this->flatComponents = $this->flattenComponents($this->components);

        $this->processComponentTreeCalculations($this->components);
        $this->processComponentTreeConditionals($this->components, shouldForget: false);

        $this->flatComponents = $this->flatValidatableComponents($this->components);

        foreach ($this->flatComponents as $component) {
            $messageBag->merge($component->validate());
            $transformedValues->put($component->key(), $component->submissionValue());
        }

        // If any components are unknown or were removed by conditional logic, discard the corresponding value
        $this->values = $transformedValues->only(array_keys($this->flatComponents));
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
        foreach ($this->values as $component) {
            if (is_array($component)) { //files always present as multivalued
                foreach ($component as $subComponent) {
                    if (is_array($subComponent) && array_key_exists('storage', $subComponent)) {
                        //get storage driver and check if file exists
                        $storageDriver = resolve(resolve(FileComponentRegistry::class)->get($subComponent['storage']));
                        if ($storageDriver->findObject($subComponent['key'])) {
                            $list[] = $subComponent;
                        }
                    }
                }
            }
        }

        return $list;
    }

    /**
     * @param ComponentInterface[] $components
     */
    private function populateComponentTreeWithData(array $components, array $data): void
    {
        foreach ($components as $component) {
            if (Arr::has($data, $component->key())) {
                $component->setSubmissionValue(Arr::get($data, $component->key()));
            }

            $this->populateComponentTreeWithData($component->components(), $data);
        }
    }

    /**
     * @param ComponentInterface[] $components
     */
    private function processComponentTreeCalculations(array $components): void
    {
        $values = $this->valuesWhileProcessingForm();

        foreach ($components as $component) {
            if ($component->isCalculated()) {
                $calculation = $component->calculation();

                $component->setSubmissionValue($calculation($values));
            }

            $this->processComponentTreeCalculations($component->components());
        }
    }

    /**
     * @param ComponentInterface[] $components
     */
    private function processComponentTreeConditionals(array $components, bool $shouldForget): void
    {
        $values = $this->valuesWhileProcessingForm();

        foreach ($components as $component) {
            // Once we're in forget mode, forget all the children recursively.
            if ($shouldForget) {
                $this->forgetComponentKeys[] = $component->key();
                $this->processComponentTreeConditionals($component->components(), shouldForget: true);

                continue;
            }

            if ($component->hasConditional()) {
                $condition = $component->conditional();

                if (! $condition($values)) {
                    $this->forgetComponentKeys[] = $component->key();
                    $this->processComponentTreeConditionals($component->components(), shouldForget: true);

                    continue;
                }
            }

            $this->processComponentTreeConditionals($component->components(), shouldForget: false);
        }
    }

    /**
     * @param ComponentInterface[] $components
     */
    private function flatValidatableComponents(array $components): array
    {
        return collect($this->flattenComponents($components))
            ->reject(fn (ComponentInterface $c) => in_array($c->key(), $this->forgetComponentKeys))
            ->filter(fn (ComponentInterface $c) => $c->canValidate())
            ->all();
    }

    /**
     * Generates the values from the components' submissionValue.
     *
     * This can be run multiple times during a recursive function so the freshest values are available at each step.
     */
    private function valuesWhileProcessingForm(): array
    {
        return collect($this->flatComponents)
            ->mapWithKeys(fn (ComponentInterface $c, string $key) => [$key => $c->submissionValue()])
            ->all();
    }

    /**
     * Return a list of components that should be validated.
     *
     * Calculations will be run on the unvalidated data before processing
     * the conditionals (just as they would be in the UI), since those
     * values may be needed for the conditional logic.
     *
     * Conditional logic that excludes fields will be evaluated here
     * and may remove components from consideration.
     *
     * @return ComponentInterface[]
     */
    protected function processComponentTree(array $components, array $values): array
    {
        // Populate the components with their data so we can evaluate conditionals
        $data = collect($values)->only($components->keys());
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
