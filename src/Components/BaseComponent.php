<?php

namespace Northwestern\SysDev\DynamicForms\Components;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag as MessageBagImpl;
use Illuminate\Validation\Factory;

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

        return $this->canValidate()
            ? $this->processValidations($fieldKey, $validator)
            : new MessageBagImpl;
    }

    /**
     * Populates the error bag with validation failures.
     *
     * If you have a layout component (canValidate() returns false),
     * this does not need to be implemented -- the validate() method
     * is smart enough not to call this!
     *
     * @param string $fieldKey Field key, taking into account custom labels
     * @param Factory $validator Illuminate validation factory, usage of which is optional (but common!)
     * @return MessageBag
     */
    protected function processValidations(string $fieldKey, Factory $validator): MessageBag
    {
        throw new ValidationNotImplementedError($this->type());
    }

    protected function validation(string $name): mixed
    {
        return Arr::get($this->validations, $name);
    }
}
