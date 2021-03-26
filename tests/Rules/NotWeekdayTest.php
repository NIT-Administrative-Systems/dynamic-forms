<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Rules;

use Northwestern\SysDev\DynamicForms\Rules\NotWeekday;
use Tests\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Rules\NotWeekday
 */
class NotWeekdayTest extends TestCase
{
    /**
     * @covers ::message
     */
    public function testMessage(): void
    {
        $rule = new NotWeekday;

        $this->assertStringContainsString(':attribute', $rule->message());
    }

    /**
     * @dataProvider passesDataProvider
     * @covers ::passes
     */
    public function testPasses(string $value, bool $passes): void
    {
        $this->assertEquals($passes, (new NotWeekday)->passes('Test', $value));
    }

    public function passesDataProvider(): array
    {
        return [
            'tuesday fails' => ['2021-03-30', false],
            'saturday passes' => ['2021-03-27', true],
            'sunday passes' => ['2021-03-28', true],
        ];
    }
}
