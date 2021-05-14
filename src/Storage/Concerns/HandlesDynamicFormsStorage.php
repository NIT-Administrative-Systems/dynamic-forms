<?php

namespace Northwestern\SysDev\DynamicForms\Storage\Concerns;

use Illuminate\Http\Request;
use Northwestern\SysDev\DynamicForms\Rules\FileExists;
use Northwestern\SysDev\DynamicForms\Storage\S3Driver;
use Northwestern\SysDev\DynamicForms\Storage\StorageInterface;

/**
 * Trait providing the upload/download actions for a controller.
 *
 * The stubs/DynamicFormsStorageController.stub file utilizes this trait.
 */
trait HandlesDynamicFormsStorage
{
    /**
     * Generates a pre-signed upload URL.
     */
    public function storeS3(Request $request)
    {
        $fileKey = $request->get('name');
        $this->authorizeFileAction('upload', $fileKey, $request, FileExists::STORAGE_S3);

        return $this->storageDriver()->getUploadLink($fileKey);
    }

    /**
     * Provides a download URL.
     *
     * This can be invoked in two ways, one of which will yield a redirect instead of JSON.
     */
    public function showS3(Request $request, ?string $fileKey = null)
    {
        // Can enter this method from a GET with a ?key={key}, or from a GET with /{key}.
        // The second one indicates a direct download, in which case we need to send a redirect.
        $needsRedirect = $fileKey !== null;
        $fileKey = $fileKey ?: $request->get('key');

        $this->authorizeFileAction('download', $fileKey, $request, FileExists::STORAGE_S3);

        return $needsRedirect
            ? redirect($this->storageDriver()->getDirectDownloadLink($fileKey))
            : $this->storageDriver()->getDownloadLink($fileKey);
    }

    /**
     * Gets the dynamic forms storage driver.
     */
    protected function storageDriver(): StorageInterface
    {
        return app()->make(S3Driver::class);
    }
}
