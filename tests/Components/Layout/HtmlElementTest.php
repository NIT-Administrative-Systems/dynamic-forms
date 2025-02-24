<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Layout;

use Northwestern\SysDev\DynamicForms\Components\Layout\HtmlElement;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\BaseComponentTestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\Northwestern\SysDev\DynamicForms\Components\Layout\HtmlElement::class)]
class HtmlElementTest extends BaseComponentTestCase
{
    protected string $componentClass = HtmlElement::class;
}
