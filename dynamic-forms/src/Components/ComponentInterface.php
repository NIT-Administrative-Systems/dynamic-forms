<?php

namespace Northwestern\SysDev\DynamicForms\Components;

use Illuminate\Contracts\Support\MessageBag;
use Northwestern\SysDev\DynamicForms\Conditional\ConditionalInterface;

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
     * @param bool $hasMultipleValues
     * @param array|null $conditional
     * @param string|null $customConditional
     * @param array $additional Other fields from the component definition (catch-all)
     */
    public function __construct(
        string $key,
        ?string $label,
        array $components,
        array $validations,
        bool $hasMultipleValues,
        ?array $conditional,
        ?string $customConditional,
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

    /**
     * Whether or not this field is set for multiple values.
     *
     * Multi-value fields will wrap their typical ::submissionValue()s in
     * an array. There are potentially an unlimited quantity of values.
     */
    public function hasMultipleValues(): bool;

    /**
     * Whether this field is shown conditionally, by way of a condition.
     *
     * This should check bot the simple/JSON logic conditions, as well as the custom conditionals
     * with JS.
     */
    public function hasConditional(): bool;

    /**
     * Returns an invokable Conditional instance.
     */
    public function conditional(): ?ConditionalInterface;
}
