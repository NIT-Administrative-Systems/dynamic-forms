<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Rules;

use Northwestern\SysDev\DynamicForms\Rules\TimeFormat;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Rules\TimeFormat
 */
class TimeFormatTest extends TestCase
{
    /**
     * @dataProvider passesDataProvider
     * @covers ::__construct
     * @covers ::passes
     */
    public function testPasses(?string $format, string $value, bool $passes): void
    {
        $check = $format ? new TimeFormat($format) : new TimeFormat;

        $this->assertEquals($passes, $check->passes('test', $value));
    }

    public function passesDataProvider(): array
    {
        return [
            'default format passes' => [null, '03:22:00', true],
            'special format passes' => ['g:i A', '12:12 PM', true],
            'fails with near-valid' => [null, '24:00:00', false],
            'fails w/ garbage data' => [null, 'garbage', false],
        ];
    }

    /**
     * @covers ::message
     */
    public function testMessage(): void
    {
        $format = new TimeFormat;
        $this->assertStringContainsString(':attribute', $format->message());
    }
}
