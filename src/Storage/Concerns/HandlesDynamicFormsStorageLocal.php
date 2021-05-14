<?php

namespace Northwestern\SysDev\DynamicForms\Storage\Concerns;

use Illuminate\Http\Request;
use Northwestern\SysDev\DynamicForms\Rules\FileExists;


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
    public function storeURL(Request $request)
    {

        $this->authorizeFileAction('upload', $request->name, $request, FileExists::STORAGE_URL);
        $request->file('file')->storeAs('uploaded', $request->name);
    }

    /**
     * Returns the given file
     */
    public function showURL(Request $request, ?string $fileKey = null)
    {
        $this->authorizeFileAction('download', $request->form, $request, FileExists::STORAGE_URL);
        return response()->download(storage_path('app/uploaded'.$request->form));
    }

    public function deleteURL(Request $request)
    {
        $this->authorizeFileAction('delete', $request->form, $request, FileExists::STORAGE_URL);
        \File::delete(storage_path('app/uploaded'.$request->form));
        return response()->noContent();
    }

}
