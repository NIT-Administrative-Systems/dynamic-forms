<?php


namespace Northwestern\SysDev\DynamicForms\Storage;


use Illuminate\Http\JsonResponse;

class FileDriver implements StorageInterface
{

    /**
     * @inheritDoc
     */
    public function findObject(string $key): bool
    {
        return \File::exists(storage_path('app/uploaded/'.$key));
    }

    /**
     * @inheritDoc
     */
    public function getDownloadLink(string $key, ?string $originalName = null): JsonResponse
    {
        // TODO: Implement getDownloadLink() method.
    }

    /**
     * @inheritDoc
     */
    public function getDirectDownloadLink(string $key, ?string $originalName = null): string
    {
        // TODO: Implement getDirectDownloadLink() method.
    }

    /**
     * @inheritDoc
     */
    public function getUploadLink(string $key): JsonResponse
    {
        // TODO: Implement getUploadLink() method.
    }
}
