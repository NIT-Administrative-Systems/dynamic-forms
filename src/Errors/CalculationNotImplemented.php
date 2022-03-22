<?php

namespace Northwestern\SysDev\DynamicForms\Errors;

class CalculationNotImplemented extends \Exception
{
    const JSON = 'JSON Logic';
    const CUSTOM_JS = 'custom JavaScript';

    public function __construct(protected string $fieldKey, protected string $calculationType)
    {
        parent::__construct(sprintf(
            'calculateValue type %s not supported on field %s',
            $this->conditionalType,
            $this->fieldKey
        ));
    }
}
