<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Str;

class ServiceDownError extends Exception
{
    const API_DIRECTORY_SEARCH = 'Directory Search';

    public function __construct(protected string $serviceName, protected ?string $additionalMessage = null, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'Required service %s is unavailable: %s',
                $this->serviceName,
                Str::limit($this->additionalMessage) ?: 'error'
            ),
            $code,
            $previous
        );
    }
}
