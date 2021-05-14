<?php

namespace Northwestern\SysDev\DynamicForms\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Northwestern\SysDev\DynamicForms\Errors\UnknownStorageDriverError;
use Northwestern\SysDev\DynamicForms\Storage\StorageInterface;

class FileExists implements Rule
{
    /** @var string */
    const STORAGE_S3 = 's3';

    /** @var string */
    const STORAGE_URL = 'url';

    public function __construct(
        protected StorageInterface $interface
    ) {
        //
    }

    public function passes($attribute, $value)
    {
        $driverName = Arr::get($value, 'storage');
        if ($driverName == self::STORAGE_S3) {
            // Check if all fields exist
            if (! isset($value) || ! isset($value['name']) || ! isset($value['key']) || ! isset($value['url'])) {
                return false;
            }

            // Check consistency of fields
            $expectedUrl = route('dynamic-forms.S3-file-redirect', ['fileKey' => $value['name']], false);
            if ($value['name'] != $value['key'] || $expectedUrl != $value['url']) {
                return false;
            }
        }
        elseif ($driverName == self::STORAGE_URL)
        {
            // Check if all fields exist
            if (! isset($value) || ! isset($value['name']) || ! isset($value['url']) || ! isset($value['data']) ||
                ! isset($value['data']['baseUrl']) || ! isset($value['data']['project']) || ! isset($value['data']['form'])) {
                return false;
            }

            // Check consistency of fields
            $expectedUrl = route('dynamic-forms.url-file-download').'?baseUrl='.urlencode($value['data']['baseUrl']).'&project=&form=/'.$value['name'];
            if ($expectedUrl != $value['url']) {
                return false;
            }
        }
        else
        {
            throw new UnknownStorageDriverError($driverName);
        }
        // Check if file exists
        return $this->interface->findObject($value['name']);
    }

    public function message()
    {
        return ':attribute was not uploaded please, remove and try again.';
    }
}
