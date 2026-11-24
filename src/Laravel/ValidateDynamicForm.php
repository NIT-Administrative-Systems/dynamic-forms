<?php

namespace Northwestern\SysDev\DynamicForms\Laravel;

use Closure;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Northwestern\SysDev\DynamicForms\ComponentRegistry;
use Northwestern\SysDev\DynamicForms\DefinitionCheckRegistry;
use Northwestern\SysDev\DynamicForms\FileComponentRegistry;
use Northwestern\SysDev\DynamicForms\Forms\Form;
use Northwestern\SysDev\DynamicForms\ResourceRegistry;

/**
 * Implements the `Request::validateDynamicForm()` macro for Laravel applications.
 *
 * Validates a submission against a form definition, returning the validated values. A
 * {@see ValidationException} redirecting back to the previous page is thrown when validation fails.
 */
class ValidateDynamicForm
{
    public static function macro(): Closure
    {
        return function (
            string $definitionJson,
            string $submissionJson,
            ?ResourceRegistry $resourceRegistry = null,
            ?ComponentRegistry $componentRegistry = null,
            ?FileComponentRegistry $fileComponentRegistry = null,
            ?DefinitionCheckRegistry $definitionCheckRegistry = null,
        ): array {
            $form = new Form(
                definitionJson: $definitionJson,
                resourceRegistry: $resourceRegistry,
                componentRegistry: $componentRegistry,
                fileComponentRegistry: $fileComponentRegistry,
                definitionCheckRegistry: $definitionCheckRegistry,
            );

            $validator = $form->validate(submissionJson: $submissionJson);

            if (! $validator->isValid()) {
                /** @var Redirector $redirector */
                $redirector = resolve(Redirector::class);

                throw (new ValidationException($validator))
                    ->redirectTo($redirector->getUrlGenerator()->previous());
            }

            return $validator->values();
        };
    }
}
