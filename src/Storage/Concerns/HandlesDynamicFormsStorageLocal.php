<?php

namespace Northwestern\SysDev\DynamicForms\Storage\Concerns;

use Illuminate\Http\Request;
use Northwestern\SysDev\DynamicForms\Storage\FileDriver;


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
        $this->authorizeFileAction('upload', $request->name, $request, FileDriver::STORAGE_URL);
        $request->file('file')->storeAs('uploaded', $request->name);
    }

    /**
     * Returns the given file
     */
    public function showURL(Request $request)
    {
        $this->authorizeFileAction('download', $request->form, $request, FileDriver::STORAGE_URL);
        return response()->download(storage_path('app/uploaded'.$request->form));
    }

    public function deleteURL(Request $request)
    {
        $this->authorizeFileAction('delete', $request->form, $request, FileDriver::STORAGE_URL);
        \File::delete(storage_path('app/uploaded'.$request->form));
        return response()->noContent();
    }
    
}
