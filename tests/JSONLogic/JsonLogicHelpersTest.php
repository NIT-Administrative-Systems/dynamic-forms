<?php

namespace Northwestern\SysDev\DynamicForms\Tests\JSONLogic;

use Monolog\Test\TestCase;
use Northwestern\SysDev\DynamicForms\JSONLogic\JsonLogicHelpers;
use Northwestern\SysDev\DynamicForms\JSONLogic\LodashFunctions\___;
use Northwestern\SysDev\DynamicForms\JSONLogicInitHelper;
use PHPUnit\Framework\Attributes\CoversClass;

final class JsonLogicHelpersTest extends TestCase
{
    public function testConvertDataVars(): void
    {
        $rule = ['+' => [
            ['var' => 'data.dog'],
            ['var' => 'data.spoon'],
            [5],
        ]];

        $expected = ['+' => [
            ['var' => 'dog'],
            ['var' => 'spoon'],
            [5],
        ]];

        $this->assertEquals($expected, JsonLogicHelpers::convertDataVars($rule));
    }
}
