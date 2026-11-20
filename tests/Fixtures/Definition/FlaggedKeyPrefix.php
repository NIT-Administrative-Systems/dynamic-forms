<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Fixtures\Definition;

/**
 * A dependency for {@see FlaggedKeyCheck}, to test that checks are resolved from the container.
 */
final class FlaggedKeyPrefix
{
    public function __construct(public readonly string $value = 'flag')
    {
    }
}
