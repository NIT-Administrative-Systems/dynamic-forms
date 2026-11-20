<?php

namespace Northwestern\SysDev\DynamicForms\Forms\Definition;

enum DefinitionIssueType: string implements DefinitionIssueTypeInterface
{
    case JsonValidationInvalidStructure = 'json_validation_invalid_structure';
    case JsonValidationMissingTrue = 'json_validation_missing_true';

    public function code(): string
    {
        return $this->value;
    }

    public function severity(): DefinitionIssueSeverity
    {
        return match ($this) {
            self::JsonValidationInvalidStructure => DefinitionIssueSeverity::Error,
            self::JsonValidationMissingTrue => DefinitionIssueSeverity::Warning,
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::JsonValidationInvalidStructure => 'Custom JSON Logic validation must use an "if" array with exactly three arguments: a condition and two outputs.',
            self::JsonValidationMissingTrue => 'Neither output of the JSON Logic "if" is true. This validation rule may never pass.',
        };
    }
}
