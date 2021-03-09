<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\ServiceDownError;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Exceptions\ServiceDownError
 */
class ServiceDownErrorTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testIsThrowable(): void
    {
        $this->expectException(ServiceDownError::class);
        $this->expectExceptionMessage(ServiceDownError::API_DIRECTORY_SEARCH);

        throw new ServiceDownError(ServiceDownError::API_DIRECTORY_SEARCH);
    }
}
