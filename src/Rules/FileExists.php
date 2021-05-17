<?php

namespace Northwestern\SysDev\DynamicForms\Rules;

use Illuminate\Contracts\Validation\Rule;
use Northwestern\SysDev\DynamicForms\Storage\StorageInterface;

class FileExists implements Rule
{
    public function __construct(
        protected StorageInterface $interface
    ) {
        //
    }

    public function passes($attribute, $value)
    {
        return $this->interface->isValid($value);
    }

    public function message()
    {
        return ':attribute was not uploaded please, remove and try again.';
    }
}
