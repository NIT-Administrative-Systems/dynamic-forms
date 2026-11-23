<?php

namespace Northwestern\SysDev\DynamicForms\Forms\Definition\Checks;

use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionCheckResult;
use Northwestern\SysDev\DynamicForms\Validation\JSONValidation;

/**
 * Checks components' custom JSON Logic validation rules.
 */
final class JsonValidationCheck implements ComponentDefinitionCheck
{
    public function check(ComponentInterface $component): DefinitionCheckResult
    {
        $validation = $component->advancedValidations();

        return $validation instanceof JSONValidation
            ? $validation->checkDefinition($component)
            : new DefinitionCheckResult;
    }
}
