<?php

namespace Northwestern\SysDev\DynamicForms\Laravel;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;
use Northwestern\SysDev\DynamicForms\Errors\UnknownComponentError;
use Northwestern\SysDev\DynamicForms\Errors\UnknownResourceError;
use Northwestern\SysDev\DynamicForms\Errors\UnknownStorageDriverError;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssue;
use Northwestern\SysDev\DynamicForms\Forms\Form;
use Northwestern\SysDev\DynamicForms\ResourceRegistry;

/**
 * Laravel validation rule for a Form.io form definition JSON string.
 *
 * The definition fails if it cannot be loaded, or if {@see Form::checkDefinition()} finds any issues. Pass
 * `failOnWarnings: false` to allow definitions that only have warnings to pass validation.
 *
 * Applications *should* still show warnings to users when allowing warnings through the validation.
 */
class ValidFormDefinition implements ValidationRule
{
    public function __construct(
        protected bool $failOnWarnings = true,
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
            | UnknownResourceError
            | UnknownStorageDriverError $e
        ) {
            // This SHOULDN'T happen if the Formio builder is configured per the package's defaults. It will fail, but
            // the exception will still be sent to the logger for follow-up.
            $fail('The :attribute could not be loaded.');
            report($e);

            return;
        }

        $checked = $form->checkDefinition();

        foreach ($this->failOnWarnings ? $checked->all() : $checked->errors() as $issue) {
            $fail($this->message($issue));
        }
    }

    protected function message(DefinitionIssue $issue): string
    {
        $message = $issue->isFormIssue()
            ? $issue->description()
            : "{$issue->componentDisplayName()}: {$issue->description()}";

        return $issue->details === null ? $message : "{$message} {$issue->details}";
    }
}
