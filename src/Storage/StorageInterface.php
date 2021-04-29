<?php

namespace Northwestern\SysDev\DynamicForms\Storage;

use Illuminate\Http\JsonResponse;

interface StorageInterface
{
    /**
     * Used to see if key is stored in our bucket.
     */
    public function findObject(string $key): bool;

    /**
     * Makes a presigned GET link for the resource.
     */
    public function getDownloadLink(string $key, ?string $originalName = null): JsonResponse;

    /**
     * Makes a presigned GET link directly for the resource.
     */
    public function getDirectDownloadLink(string $key, ?string $originalName = null): string;

    /**
     * Makes a presigned PUT link for the resource.
     */
    public function getUploadLink(string $key): JsonResponse;
}
