<?php

namespace App\Exceptions;

use Exception;

class ServiceDownError extends Exception
{
    const API_DIRECTORY_SEARCH = 'Directory Search';

    public function __construct(protected string $serviceName, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(sprintf('Required service %s is unavailable', $this->serviceName), $code, $previous);
    }
}
