<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Rules;

use Northwestern\SysDev\DynamicForms\Rules\NotWeekend;
use Tests\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Rules\NotWeekend
 */
class NotWeekendTest extends TestCase
{
    /**
     * @covers ::message
     */
    public function testMessage(): void
    {
        $rule = new NotWeekend();

        $this->assertStringContainsString(':attribute', $rule->message());
    }

    /**
     * @dataProvider passesDataProvider
     * @covers ::passes
     */
    public function testPasses(string $value, bool $passes): void
    {
        $this->assertEquals($passes, (new NotWeekend)->passes('Test', $value));
    }

    public function passesDataProvider(): array
    {
        return [
            'tuesday passes' => ['2021-03-30', true],
            'saturday fails' => ['2021-03-27', false],
            'sunday days' => ['2021-03-28', false],
        ];
    }
}
