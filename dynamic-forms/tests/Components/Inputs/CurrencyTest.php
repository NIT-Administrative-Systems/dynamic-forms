<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\Inputs\Currency;
use Northwestern\SysDev\DynamicForms\Tests\Components\InputComponentTestCase;

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

    /**
     * @dataProvider submissionValueDataProvider
     * @covers ::submissionValue
     */
    public function testSubmissionValue(int | float | array $submissionValue, bool $hasMultipleValues, array | int | float $expected): void
    {
        $currency = $this->getComponent(hasMultipleValues: $hasMultipleValues, submissionValue: $submissionValue);

        $this->assertEquals($expected, $currency->submissionValue());
    }

    public function submissionValueDataProvider(): array
    {
        return [
            'integer is untouched' => [100, false, 100],
            'two digit float, untouched' => [100.01, false, 100.01],
            'truncated at two digits' => [100.991, false, 100.99],
            'multiple works' => [[100, 100.01, 100.991], true, [100, 100.01, 100.99]],
        ];
    }
}
