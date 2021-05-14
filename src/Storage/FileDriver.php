<?php


namespace Northwestern\SysDev\DynamicForms\Storage;

class FileDriver implements StorageInterface
{
    /**
     * @inheritDoc
     */
    public function findObject(string $key): bool
    {
        return \File::exists(storage_path('app/uploaded/'.$key));
    }
}
