<?php

namespace Northwestern\SysDev\DynamicForms\Forms;

enum FormWarningType: string
{
    case JsonValidationInvalidStructure = 'json_validation_invalid_structure';
    case JsonValidationMissingTrue = 'json_validation_missing_true';
}
