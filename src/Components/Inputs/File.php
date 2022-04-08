<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\Components\UploadInterface;
use Northwestern\SysDev\DynamicForms\RuleBag;
use Northwestern\SysDev\DynamicForms\Rules\FileExists;
use Northwestern\SysDev\DynamicForms\Storage\StorageInterface;

class File extends BaseComponent implements UploadInterface
{
    const TYPE = 'file';
    protected StorageInterface $storage;

    protected function processValidations(string $fieldKey, string $fieldLabel, mixed $submissionValue, Factory $validator): MessageBag
    {
        $rules = new RuleBag($fieldKey, []);
        if ($this->validation('required')) {
            $rules->add('required');
        } else {
            $rules->add('nullable');
        }
        $rules->add(new FileExists($this->getStorageDriver()));

        return $validator->make(
            [$fieldKey => $submissionValue],
            $rules->rules(),
            [],
            [$fieldKey => $fieldLabel]
        )->messages();
    }

    public function getStorageDriver(): StorageInterface
    {
        return $this->storage;
    }

    public function setStorageDriver(StorageInterface $storage): void
    {
        $this->storage = $storage;
    }

    /**
     * Files always come in an array, even when in single-value mode.
     */
    protected function hasMultipleValuesForValidation(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getStorageType(): string
    {
        return $this->additional['storage'];
    }
}
