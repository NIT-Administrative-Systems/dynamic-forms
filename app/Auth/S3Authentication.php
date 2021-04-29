<?php

namespace App\Auth;

use App\Models\Form;

trait S3Authentication
{
    /**
     * Retrieve a form model for a given key.
     * This can then be used to check whether the requester has permission to view file associated with the given key.
     * Will most likely be instantiated with a FormRepo.
     */
    protected function findFormFromS3Key(string $key): ?Form
    {
        throw new \Exception('findFormFromS3Key is not implemented, but must be implemented.');
    }
}
