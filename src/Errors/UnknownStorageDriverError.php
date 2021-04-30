<?php

namespace Northwestern\SysDev\DynamicForms\Errors;

class UnknownStorageDriverError extends \Exception
{
    public function __construct(
        protected ?string $driverName
    ) {
        parent::__construct(sprintf('Unknown storage driver %s requested', $this->driverName));
    }
}
