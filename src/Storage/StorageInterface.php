<?php

namespace Northwestern\SysDev\DynamicForms\Storage;

interface StorageInterface
{
    /**
     * Used to see if key is stored in the storage location.
     */
    public function findObject(string $key): bool;

    /**
     * Used to validate a storage request for this method.
     */
    public function isValid(mixed $value): bool;

    /**
     * Returns the StorageMethod being implemented by this interface
     * (supported values are dropbox, azure, indexeddb, s3 (already implemented), url (already implemented)).
     */
    public static function getStorageMethod(): string;
}
