<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Layout;

use Northwestern\SysDev\DynamicForms\Components\Layout\Content;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\BaseComponentTestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\Northwestern\SysDev\DynamicForms\Components\Layout\Content::class)]
class ContentTest extends BaseComponentTestCase
{
    protected string $componentClass = Content::class;
}
