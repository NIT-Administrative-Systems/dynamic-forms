<?php

namespace Northwestern\SysDev\DynamicForms\Errors;

class InvalidDefinitionError extends \Exception
{
    public function __construct(
        string $message,
        protected string $json_path
    ) {
        parent::__construct(sprintf('[%s] %s', $this->json_path, $message));
    }
}
