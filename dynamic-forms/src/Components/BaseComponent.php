<?php

namespace Northwestern\SysDev\DynamicForms\Components;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag as MessageBagImpl;
use Illuminate\Validation\Factory;
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
        protected array $components,
        protected array $validations,
        protected bool $hasMultipleValues,
        protected array $additional,
    ) {
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

    public function components(): array
    {
        return $this->components;
    }

    public function hasMultipleValues(): bool
    {
        return $this->hasMultipleValues;
    }

    public function submissionValue(): mixed
    {
        return $this->submissionValue;
    }

    public function setSubmissionValue(mixed $value): void
    {
        $this->submissionValue = $value;
    }

    public function validate(): MessageBag
    {
        $fieldKey = $this->label() ?? $this->key();
        $validator = app()->make('validator');
        $bag = new MessageBagImpl;

        if (! $this->canValidate()) {
            return $bag;
        }

        if ($this->hasMultipleValues()) {
            foreach ($this->submissionValue() as $index => $submissionValue) {
                $bag->merge($this->processValidations(
                    sprintf('%s %s', $fieldKey, $index),
                    $submissionValue,
                    $validator
                ));
            }

            return $bag->merge($this->postProcessValidationsForMultiple($fieldKey));
        }

        return $this->processValidations($fieldKey, $this->submissionValue(), $validator);
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
    protected function processValidations(string $fieldKey, mixed $submissionValue, Factory $validator): MessageBag
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
        if (! $this->hasMultipleValues()) {
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
