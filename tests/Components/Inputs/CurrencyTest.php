<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use PHPUnit\Framework\Attributes\DataProvider;
use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Currency;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Currency
 */
class CurrencyTest extends InputComponentTestCase
{
    protected string $componentClass = Currency::class;

    public function validationsProvider(): array
    {
        return [
            'passes with blank data' => [[], '', true],
            'required passes' => [['required' => true], 100, true],
            'required fails' => [['required' => true], '', false],
        ];
    }

    #[DataProvider('submissionValueNumericsDataProvider')]
    public function testSubmissionValueHandlesNumerics(int | float | array $submissionValue, bool $hasMultipleValues, array | int | float $expected): void
    {
        $currency = $this->getComponent(hasMultipleValues: $hasMultipleValues, submissionValue: $submissionValue);

        $this->assertEquals($expected, $currency->submissionValue());
    }

    public function submissionValueNumericsDataProvider(): array
    {
        return [
            'integer is untouched' => [100, false, 100],
            'two digit float, untouched' => [100.01, false, 100.01],
            'truncated at two digits' => [100.991, false, 100.99],
            'multiple works' => [[100, 100.01, 100.991], true, [100, 100.01, 100.99]],
        ];
    }

    public function submissionValueProvider(): array
    {
        return [
            'no transformations' => [null, 1.00, 1.00],
            'upper' => [CaseEnum::UPPER, 1.00, 1.00],
            'lower' => [CaseEnum::LOWER, 1.00, 1.00],
        ];
    }
}
