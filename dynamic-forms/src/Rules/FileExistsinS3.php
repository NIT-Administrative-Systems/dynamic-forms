<?php

namespace Northwestern\SysDev\DynamicForms\Rules;

use Illuminate\Contracts\Validation\Rule;
use Northwestern\SysDev\DynamicForms\Storage\StorageInterface;

class FileExistsinS3 implements Rule
{
    /** @var string */
    const STORAGE_S3 = 's3';

    public function __construct(
        protected StorageInterface $interface
    ) {
    }

    public function passes($attribute, $value)
    {
        //Check if all fields exist
        if (! isset($value) || ! isset($value['storage']) || ! isset($value['name']) || ! isset($value['key'])
            || ! isset($value['url']) || $value['storage'] != self::STORAGE_S3) {
            return false;
        }
        //Check consistency of fields
        if ($value['name'] != $value['key'] || (url('/storage/s3/').'/'.$value['name']) != $value['url']) {
            return false;
        }
        //Check if file exists
        return $this->interface->findObject($value['name']);
    }

    public function message()
    {
        return ':attribute was not uploaded please, remove and try again.';
    }
}
