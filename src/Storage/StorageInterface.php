<?php

namespace Northwestern\SysDev\DynamicForms\Storage;


interface StorageInterface
{
    /**
     * Used to see if key is stored in the storage location.
     */
    public function findObject(string $key): bool;
}
