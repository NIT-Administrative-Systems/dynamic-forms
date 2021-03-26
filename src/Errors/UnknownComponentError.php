<?php

namespace Northwestern\SysDev\DynamicForms\Errors;

class UnknownComponentError extends \Exception
{
    public function __construct(protected string $type)
    {
        parent::__construct(sprintf('Component "%s" is unknown', $this->type));
    }
}
