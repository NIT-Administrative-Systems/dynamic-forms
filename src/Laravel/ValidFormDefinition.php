<?php

namespace Northwestern\SysDev\DynamicForms\Laravel;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Northwestern\SysDev\DynamicForms\Errors\CalculationNotImplemented;
use Northwestern\SysDev\DynamicForms\Errors\ConditionalNotImplemented;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;
use Northwestern\SysDev\DynamicForms\Errors\UnknownComponentError;
use Northwestern\SysDev\DynamicForms\Errors\ValidationNotImplementedError;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssue;
use Northwestern\SysDev\DynamicForms\Forms\Form;
use Northwestern\SysDev\DynamicForms\ResourceRegistry;

/**
 * Laravel validation rule for a Form.io form definition JSON string.
 *
 * The definition fails if it cannot be loaded, or if {@see Form::checkDefinition()} finds any issues. Pass
 * `errorsOnly: true` to allow definitions that only have warnings to pass validation.
 *
 * Applications *should* still show warnings to users when allowing warnings through the validation.
 */
class ValidFormDefinition implements ValidationRule
{
    public function __construct(
        protected bool $errorsOnly = false,
        protected ?ResourceRegistry $resourceRegistry = null,
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute must be a form definition JSON string.');

            return;
        }

        try {
            $form = new Form($value, $this->resourceRegistry);
        } catch (
            InvalidDefinitionError
            | UnknownComponentError
            | ValidationNotImplementedError
            | ConditionalNotImplemented
            | CalculationNotImplemented $e
        ) {
            // This SHOULDN'T happen if the Formio builder is configured per the package's defaults. It will fail, but
            // the exception will still be sent to the logger for follow-up.
            $fail('The :attribute could not be loaded.');
            report($e);

            return;
        }

        $checked = $form->checkDefinition();
        $components = $form->flatComponents();

        foreach ($this->errorsOnly ? $checked->errors() : $checked->all() as $issue) {
            $label = ($components[$issue->componentKey] ?? null)?->label();

            $fail($this->message($issue, $label));
        }
    }

    protected function message(DefinitionIssue $issue, ?string $label): string
    {
        $component = $label === null || $label === ''
            ? $issue->componentKey
            : "{$label} ({$issue->componentKey})";

        $message = "{$component}: {$issue->description()}";

        return $issue->details === null ? $message : "{$message} {$issue->details}";
    }
}
