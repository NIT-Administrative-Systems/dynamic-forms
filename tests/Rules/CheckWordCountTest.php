<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Rules;

use PHPUnit\Framework\Attributes\DataProvider;
use Northwestern\SysDev\DynamicForms\Rules\CheckWordCount;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Rules\CheckWordCount
 */
final class CheckWordCountTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testConstructThrowsInvalidMode(): void
    {
        $this->expectException(\TypeError::class);

        new CheckWordCount('dog', 10);
    }

    #[DataProvider('dataProvider')]
    public function testPasses(string $mode, int $length, string $submissionValue, bool $passes, string $message = null): void
    {
        $rule = new CheckWordCount($mode, $length);

        $this->assertEquals($passes, $rule->passes('attribute', $submissionValue));
        $this->assertEquals($message, $rule->message());
    }

    public static function dataProvider(): array
    {
        $short = 'Short and sweet';
        $long = 'Short and sweet is not the point,  this is a  word counter  you know';

        return [
            // mode, length, submissionValue, passes, message
            'max passing' => [CheckWordCount::MODE_MAXIMUM, 10, $short, true, ':attribute must have no more than 10 words.'],
            'max failing' => [CheckWordCount::MODE_MAXIMUM, 10, $long, false, ':attribute must have no more than 10 words.'],
            'min passing' => [CheckWordCount::MODE_MINIMUM, 10, $long, true, ':attribute must have at least 10 words.'],
            'min failing' => [CheckWordCount::MODE_MINIMUM, 10, $short, false, ':attribute must have at least 10 words.'],
        ];
    }
}
