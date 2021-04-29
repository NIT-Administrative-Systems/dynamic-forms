<?php

namespace App\Http\Controllers;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use Laravel\Vapor\Http\Controllers\SignedStorageUrlController;
use Northwestern\SysDev\DynamicForms\Storage\S3Driver;

/**
 * @Saood - using SignedStorageUrlController since it's got helpful functionality!
 *
 * But the JSON it responds w/ from its ::store() method needs to be a little different
 * for FormIO compatability.
 *
 * We'll also need to put some stuff in here for viewing the files, so this probably
 * won't be an invokable controller in the end.
 *
 * The viewing works similarly though -- we make a pre-signed URL and feed it back to
 * the client, either as a redirect or as a JSON thinggy (not sure which).
 *
 * I'd also like to know how we can reconfigure the Form component to use a different
 * URL. The /storage/s3 thing would ideally be under my dynamic-forms/ route group instead
 * of at /storage.
 *
 * This controller is also unauthenticated. It should be authenticated, but you'll need to get
 * the FormIO AJAX requests to include two things: the `withCredentials: true` flag (so the browser
 * will include cookies -- if it's not already, haven't checked), and an additional header with
 * the CSRF token. I had to put this route in the VerifyCsrfToken middleware's exemption to get
 * it to work, but that's not what we want to be doing.
 */
class FormStorageController extends SignedStorageUrlController
{
    public function __invoke(Request $request, FilesystemManager $storageManager)
    {
        $this->ensureEnvironmentVariablesAreAvailable($request);

        /*
         * @TODO
        Gate::authorize('uploadFiles', [
            $request->user(),
            $bucket = $request->input('bucket') ?: config('filesystems.disks.s3.bucket'),
        ]);
         */
        //swap implementation to use interface with form data to determine key validity
        //form for permissions driver for storage driver type and key as a filekey and a resource key
        return (new S3Driver())->getUploadLink($request->name);
    }

    public function getS3(Request $request, FilesystemManager $storageManager)
    {
        $this->ensureEnvironmentVariablesAreAvailable($request);

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
        return (new S3Driver())->getDownloadLink($request->key);
    }

    public function getS3direct(Request $request, FilesystemManager $storageManager)
    {
        $this->ensureEnvironmentVariablesAreAvailable($request);

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
        return redirect((new S3Driver())->getDirectDownloadLink($request->key));
    }
}
