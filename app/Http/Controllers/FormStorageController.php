<?php

namespace App\Http\Controllers;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use Northwestern\SysDev\DynamicForms\Storage\S3Driver;
use Northwestern\SysDev\DynamicForms\Storage\StorageInterface;

class FormStorageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request)
    {
        /*
         * @TODO
        Gate::authorize('uploadFiles', [
            $request->user(),
            $bucket = $request->input('bucket') ?: config('filesystems.disks.s3.bucket'),
        ]);
         */

        //swap implementation to use interface with form data to determine key validity
        //form for permissions driver for storage driver type and key as a filekey and a resource key
        return $this->storageDriver()->getUploadLink($request->name);
    }

    public function getS3(Request $request)
    {
        /*
         * @TODO check permissions and grab original key
         *      swap implementation to use interface with form data to determine key validity
         * form for permissions driver for storage driver type and key as a filekey and a resource key
        Gate::authorize('uploadFiles', [
            $request->user(),
            $bucket = $request->input('bucket') ?: config('filesystems.disks.s3.bucket'),
        ]);
         */
        //Will have to query user to find the key and original name alongisde it.
        return $this->storageDriver()->getDownloadLink($request->key);
    }

    public function getS3direct(Request $request, FilesystemManager $storageManager)
    {
        /*
         * @TODO check permissions and grab original key
         *      swap implementation to use interface with form data to determine key validity
         * form for permissions driver for storage driver type and key as a filekey and a resource key
        Gate::authorize('uploadFiles', [
            $request->user(),
            $bucket = $request->input('bucket') ?: config('filesystems.disks.s3.bucket'),
        ]);
         */
        //Will have to query key to find the original name alongisde it.
        return redirect($this->storageDriver()->getDirectDownloadLink($request->key));
    }

    protected function storageDriver(): StorageInterface
    {
        return app()->make(S3Driver::class);
    }
}
