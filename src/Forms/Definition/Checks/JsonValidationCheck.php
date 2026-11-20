<?php

namespace Northwestern\SysDev\DynamicForms\Forms\Definition\Checks;

use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Northwestern\SysDev\DynamicForms\Forms\Definition\CheckedFormDefinition;
use Northwestern\SysDev\DynamicForms\Validation\JSONValidation;

/**
 * Checks components' custom JSON Logic validation rules.
 */
final class JsonValidationCheck implements ComponentDefinitionCheck
{
    public function check(ComponentInterface $component): CheckedFormDefinition
    {
        $validation = $component->advancedValidations();

        return $validation instanceof JSONValidation
            ? $validation->checkDefinition($component)
            : new CheckedFormDefinition;
    }
}
