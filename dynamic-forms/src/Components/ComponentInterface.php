<?php

namespace Northwestern\SysDev\DynamicForms\Components;

use Illuminate\Contracts\Support\MessageBag;

interface ComponentInterface
{
    /**
     * Return the component type.
     *
     * This name maps to the name in the Formio.Components.components object
     */
    public static function type(): string;

    /**
     * @param string $key
     * @param string|null $label
     * @param array $components
     * @param array $validations
     * @param array $additional Other fields from the component definition (catch-all)
     */
    public function __construct(
        string $key,
        ?string $label,
        array $components,
        array $validations,
        array $additional,
    );

    /**
     * Component form key.
     *
     * This is the form field name
     */
    public function key(): string;

    /**
     * Component label, if available.
     */
    public function label(): ?string;

    /**
     * Get child components.
     *
     * @return ComponentInterface[]
     */
    public function components(): array;

    /**
     * Get the value for the component.
     */
    public function submissionValue(): mixed;

    /**
     * Sets a value for the component.
     *
     * @param mixed $value
     */
    public function setSubmissionValue(mixed $value): void;

    /**
     * Whether this component can be validated in a form submission.
     *
     * The layout components (like Content) do not have inputs, and thus
     * should not be validated.
     */
    public function canValidate(): bool;

    /**
     * Run validations for the component.
     */
    public function validate(): MessageBag;
}
