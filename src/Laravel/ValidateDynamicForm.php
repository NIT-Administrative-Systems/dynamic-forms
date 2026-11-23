<?php

namespace Northwestern\SysDev\DynamicForms\Laravel;

use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
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
    public function __invoke(
        string $definitionJson,
        string $submissionJson,
        ?ResourceRegistry $resourceRegistry = null,
    ): array {
        $form = new Form($definitionJson, $resourceRegistry ?? resolve(ResourceRegistry::class));
        $validator = $form->validate($submissionJson);

        if (! $validator->isValid()) {
            /** @var Redirector $redirector */
            $redirector = resolve(Redirector::class);

            throw (new ValidationException($validator))
                ->redirectTo($redirector->getUrlGenerator()->previous());
        }

        return $validator->values();
    }
}
