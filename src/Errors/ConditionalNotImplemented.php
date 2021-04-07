<?php

namespace Northwestern\SysDev\DynamicForms\Errors;

class ConditionalNotImplemented extends \Exception
{
    const SIMPLE = 'simple';
    const JSON = 'JSON Logic';
    const CUSTOM_JS = 'custom JavaScript';

    public function __construct(protected string $fieldKey, protected string $conditionalType)
    {
        parent::__construct(sprintf(
            'Conditional type %s not supported on field %s',
            $this->conditionalType,
            $this->fieldKey
        ));
    }
}
