<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Rules;

use Northwestern\SysDev\DynamicForms\Rules\NotWeekend;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Rules\NotWeekend
 */
final class NotWeekendTest extends TestCase
{
    /**
     * @covers ::message
     */
    public function testMessage(): void
    {
        $rule = new NotWeekend();

        $this->assertStringContainsString(':attribute', $rule->message());
    }

    #[DataProvider('passesDataProvider')]
    public function testPasses(string $value, bool $passes): void
    {
        $this->assertEquals($passes, (new NotWeekend)->passes('Test', $value));
    }

    public static function passesDataProvider(): array
    {
        return [
            'tuesday passes' => ['2021-03-30', true],
            'saturday fails' => ['2021-03-27', false],
            'sunday days' => ['2021-03-28', false],
        ];
    }
}
