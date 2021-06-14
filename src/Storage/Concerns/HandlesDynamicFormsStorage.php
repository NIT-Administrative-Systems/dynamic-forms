<?php

namespace Northwestern\SysDev\DynamicForms\Storage\Concerns;

/**
 * Trait providing the upload/download actions for a controller.
 *
 * The stubs/DynamicFormsStorageController.stub file utilizes this trait.
 */
trait HandlesDynamicFormsStorage
{
    use S3Storage, LocalStorage;

}
