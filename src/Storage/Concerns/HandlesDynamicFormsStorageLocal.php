<?php

namespace Northwestern\SysDev\DynamicForms\Storage\Concerns;

use Illuminate\Http\Request;
use Northwestern\SysDev\DynamicForms\Storage\S3Driver;
use Northwestern\SysDev\DynamicForms\Storage\StorageInterface;

/**
 * Trait providing the upload/download actions for a controller.
 *
 * The stubs/DynamicFormsStorageController.stub file utilizes this trait.
 */
trait HandlesDynamicFormsStorageLocal
{
    /**
     * Stores the given request
     */
    public function store(Request $request)
    {
        $this->authorizeFileAction('upload', $request->name, $request);
        $request->file('file')->storeAs('uploaded', $request->name);
    }

    /**
     * Returns the given file
     */
    public function show(Request $request, ?string $fileKey = null)
    {
        $this->authorizeFileAction('download', $request->form, $request);
        return response()->download(storage_path('app/uploaded'.$request->form));
    }

    public function delete(Request $request)
    {
        $this->authorizeFileAction('delete', $request->form, $request);
        \File::delete(storage_path('app/uploaded'.$request->form));
        return response()->noContent();
    }

}
