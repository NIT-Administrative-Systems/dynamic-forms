<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Number;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\InputComponentTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Number
 */
final class NumberTest extends InputComponentTestCase
{
    protected string $componentClass = Number::class;

    public static function validationsProvider(): array
    {
        return [
            'required passes' => [['required' => true], 1.0, true],
            'required fails' => [['required' => true], '', false],
            'min fails' => [['min' => 10], 9, false],
            'min passes' => [['min' => 10], 11, true],
            'max fails' => [['max' => 3], 4, false],
            'max passes' => [['max' => 3], 3, true],
        ];
    }

    public static function submissionValueProvider(): array
    {
        return [
            'no transformations' => [null, 1, 1],
            'upper' => [CaseEnum::UPPER, 1, 1],
            'lower' => [CaseEnum::LOWER, 1, 1],
        ];
    }

    #[DataProvider('submissionValueNumericsDataProvider')]
    public function testSubmissionValueHandlesNumerics(mixed $submissionValue, bool $hasMultipleValues, mixed $expected, array $additionalSettings): void
    {
        $currency = $this->getComponent(
            additional: $additionalSettings,
            hasMultipleValues: $hasMultipleValues,
            submissionValue: $submissionValue
        );

        $this->assertEquals($expected, $currency->submissionValue());
    }

    public static function submissionValueNumericsDataProvider(): array
    {
        return [
            'integer is untouched' => [100, false, 100, []],
            'two digit float, untouched' => [100.01, false, 100.01, []],
            'string casts to 0' => ['', false, null, []],
            'multiple works' => [[100, 100.01, 100.991], true, [100, 100.01, 100.991], []],
            'int forced decimal' => [1, false, 1, ['requireDecimal' => true, 'decimalLimit' => 2]],
            'forced decimal, truncates places' => [1.2345, false, 1.23, ['requireDecimal' => true, 'decimalLimit' => 2]],
        ];
    }
}
