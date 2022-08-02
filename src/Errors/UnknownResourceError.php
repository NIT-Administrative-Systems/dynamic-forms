<?php

namespace Northwestern\SysDev\DynamicForms\Errors;

class UnknownResourceError extends \Exception
{
    public function __construct(protected string $type)
    {
        parent::__construct(sprintf('Resource "%s" is unknown', $this->type));
    }
}
