<?php

namespace Northwestern\SysDev\DynamicForms\Errors;

class ValidationNotImplementedError extends \Exception
{
    public function __construct(protected string $component)
    {
        parent::__construct(sprintf('processValidation is not implemented for %s', $this->component));
    }
}
