<?php

namespace Northwestern\SysDev\DynamicForms\Components;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag as MessageBagImpl;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Calculation\CalculationInterface;
use Northwestern\SysDev\DynamicForms\Calculation\JSONCalculation;
use Northwestern\SysDev\DynamicForms\Conditional\ConditionalInterface;
use Northwestern\SysDev\DynamicForms\Conditional\JSONConditional;
use Northwestern\SysDev\DynamicForms\Conditional\SimpleConditional;
use Northwestern\SysDev\DynamicForms\Errors\CalculationNotImplemented;
use Northwestern\SysDev\DynamicForms\Errors\ConditionalNotImplemented;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;
use Northwestern\SysDev\DynamicForms\Errors\ValidationNotImplementedError;

/**
 * Implements common functionality for all components.
 */
abstract class BaseComponent implements ComponentInterface
{
    protected mixed $submissionValue = null;

    public static function type(): string
    {
        return static::TYPE;
    }

    public function __construct(
        protected string $key,
        protected ?string $label,
        protected ?string $errorLabel,
        protected array $components,
        protected array $validations,
        protected bool $hasMultipleValues,
        protected ?array $conditional,
        protected ?string $customConditional,
        protected string $case,
        protected null|array|string $calculateValue,
        protected array $additional,
    ) {
        //
    }

    public function canValidate(): bool
    {
        return true;
    }

    public function key(): string
    {
        return $this->key;
    }

    public function label(): ?string
    {
        return $this->label;
    }

    public function errorLabel(): ?string
    {
        return $this->errorLabel;
    }

    public function components(): array
    {
        return $this->components;
    }

    public function hasMultipleValues(): bool
    {
        return $this->hasMultipleValues;
    }

    /**
     * Informs the validation code if this should be treated as a multi-value field.
     *
     * There are cases where components always store data as multiple values, even in single-
     * value mode. The validator needs to handle that correctly, but we do not want to inadvertently
     * perform a transformation that makes the data incompatible with viewing/editing it again.
     */
    protected function hasMultipleValuesForValidation(): bool
    {
        return $this->hasMultipleValues();
    }

    public function hasConditional(): bool
    {
        return $this->conditional || $this->customConditional;
    }

    public function conditional(): ?ConditionalInterface
    {
        if (! $this->hasConditional()) {
            return null;
        }

        if ($this->customConditional) {
            throw new ConditionalNotImplemented($this->key(), ConditionalNotImplemented::CUSTOM_JS);
        }

        if (Arr::get($this->conditional, 'json')) {
            return new JSONConditional(Arr::get($this->conditional, 'json'));
        }

        return new SimpleConditional(
            Arr::get($this->conditional, 'show'),
            Arr::get($this->conditional, 'when'),
            Arr::get($this->conditional, 'eq'),
        );
    }

    public function isCalculated(): bool
    {
        return $this->calculateValue !== null;
    }

    public function calculation(): ?CalculationInterface
    {
        if (! $this->calculateValue) {
            return null;
        }

        if (is_string($this->calculateValue)) {
            throw new CalculationNotImplemented($this->key(), CalculationNotImplemented::CUSTOM_JS);
        }

        return new JSONCalculation($this->calculateValue);
    }

    public function submissionValue(): mixed
    {
        $value = $this->submissionValue;

        foreach ($this->transformations() as $transform) {
            $value = $transform($value);
        }

        return $value;
    }

    public function setSubmissionValue(mixed $value): void
    {
        $this->submissionValue = $value;
    }

    public function validate(): MessageBag
    {
        $fieldLabel = $this->errorLabel() ?? $this->label() ?? $this->key();

        $validator = app()->make('validator');
        $bag = new MessageBagImpl;

        if (! $this->canValidate()) {
            return $bag;
        }

        if ($this->hasMultipleValuesForValidation()) {
            foreach ($this->submissionValue() as $index => $submissionValue) {
                $bag = $this->mergeErrorBags($bag, $this->processValidations(
                    $this->key(),
                    $this->errorLabel() ?? sprintf('%s (%s)', $fieldLabel, $index + 1),
                    $submissionValue,
                    $validator
                ));
            }

            return $bag->merge($this->postProcessValidationsForMultiple($this->key()));
        }

        return $this->mergeErrorBags(
            new MessageBagImpl,
            $this->processValidations($this->key(), $fieldLabel, $this->submissionValue(), $validator)
        );
    }

    /**
     * Handles merging validation error MessageBags together, accounting for custom error messages.
     *
     * A custom error message overwrites all other error messages, so any number of errors in the
     * $mergeFrom bag will be consolidated down into one error using the custom message.
     *
     * If no custom message is set for the component, this just merges the two bags together without
     * any other modification.
     */
    protected function mergeErrorBags(MessageBag $mergeInto, MessageBag $mergeFrom): MessageBag
    {
        if ($this->validation('customMessage') && $mergeFrom->isNotEmpty()) {
            $mergeFrom = new MessageBagImpl([
                Arr::first($mergeFrom->keys()) => $this->validation('customMessage'),
            ]);
        }

        return $mergeInto->merge($mergeFrom);
    }

    public function transformations(): array
    {
        $transformations = [];

        if ($this->case === CaseEnum::UPPER) {
            $transformations['case'] = fn ($value) => is_string($value) ? strtoupper($value) : $value;
        } elseif ($this->case === CaseEnum::LOWER) {
            $transformations['case'] = fn ($value) => is_string($value) ? strtolower($value) : $value;
        }

        return $transformations;
    }

    /**
     * Populates the error bag with validation failures.
     *
     * If you have a layout component (canValidate() returns false),
     * this does not need to be implemented -- the validate() method
     * is smart enough not to call this.
     *
     * When ::hasMultipleValues() is true, the validate() method will
     * call this once for each submitted value. The ::TODO() method is
     * called afterwards to do any special processing on the values in
     * aggregate; logic like that does *not* belong in this method.
     *
     * @param string $fieldKey Field key, taking into account custom labels
     * @param mixed $submissionValue One single value
     * @param Factory $validator Illuminate validation factory, usage of which is optional (but common!)
     * @return MessageBag
     */
    protected function processValidations(string $fieldKey, string $fieldLabel, mixed $submissionValue, Factory $validator): MessageBag
    {
        throw new ValidationNotImplementedError($this->type());
    }

    /**
     * When in multiple values mode, performs additional validations on the submissionValues.
     *
     * This will ensure required multiple-value fields have at least one value. Extend this method
     * if more intricate logic is needed.
     *
     * @param string $fieldKey Field key, taking into account custom labels
     * @return MessageBag
     */
    protected function postProcessValidationsForMultiple(string $fieldKey): MessageBag
    {
        if (! $this->hasMultipleValuesForValidation()) {
            throw new InvalidDefinitionError(
                sprintf('%s called but component is multiple => false; cannot process', __METHOD__),
                $this->key()
            );
        }

        $bag = new MessageBagImpl;
        if ($this->validation('required') && count($this->submissionValue) == 0) {
            $bag->add($fieldKey, __('validation.required', ['attribute' => $fieldKey]));
        }

        return $bag;
    }

    protected function validation(string $name): mixed
    {
        return Arr::get($this->validations, $name);
    }
}
