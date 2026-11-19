<?php

namespace Northwestern\SysDev\DynamicForms\Forms;

enum FormWarningType: string
{
    case JsonValidationInvalidStructure = 'json_validation_invalid_structure';
    case JsonValidationMissingTrue = 'json_validation_missing_true';

    /**
     * Human-readable explanations for the warnings.
     */
    public function description(): string
    {
        return match ($this) {
            self::JsonValidationInvalidStructure => 'Custom JSON Logic validation must use an "if" array with exactly three arguments: a condition and two outputs.',
            self::JsonValidationMissingTrue => 'Neither output of the JSON Logic "if" is true. This validation rule may never pass.',
        };
    }
}
