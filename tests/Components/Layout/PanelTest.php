<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Layout;

use Northwestern\SysDev\DynamicForms\Components\Layout\Panel;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\BaseComponentTestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\Northwestern\SysDev\DynamicForms\Components\Layout\Panel::class)]
class PanelTest extends BaseComponentTestCase
{
    protected string $componentClass = Panel::class;
}
