<?php

namespace Northwestern\SysDev\DynamicForms\Components;

use Northwestern\SysDev\DynamicForms\Storage\StorageInterface;

interface UploadInterface
{
    public function getStorageDriver(): StorageInterface;

    public function setStorageDriver(StorageInterface $storage): void;
}
