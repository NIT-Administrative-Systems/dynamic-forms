<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Rules;

use Northwestern\SysDev\DynamicForms\Rules\NotWeekday;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Rules\NotWeekday
 */
final class NotWeekdayTest extends TestCase
{
    /**
     * @covers ::message
     */
    public function testMessage(): void
    {
        $rule = new NotWeekday;

        $this->assertStringContainsString(':attribute', $rule->message());
    }

    #[DataProvider('passesDataProvider')]
    public function testPasses(string $value, bool $passes): void
    {
        $this->assertEquals($passes, (new NotWeekday)->passes('Test', $value));
    }

    public static function passesDataProvider(): array
    {
        return [
            'tuesday fails' => ['2021-03-30', false],
            'saturday passes' => ['2021-03-27', true],
            'sunday passes' => ['2021-03-28', true],
        ];
    }
}
