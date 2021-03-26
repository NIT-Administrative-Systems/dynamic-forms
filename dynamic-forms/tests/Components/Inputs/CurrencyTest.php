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
    public function testSubmissionValue(int | float $submissionValue, int | float $expected): void
    {
        $currency = $this->getComponent(submissionValue: $submissionValue);

        $this->assertEquals($expected, $currency->submissionValue());
    }

    public function submissionValueDataProvider(): array
    {
        return [
            'integer is untouched' => [100, 100],
            'two digit float, untouched' => [100.01, 100.01],
            'truncated at two digits' => [100.991, 100.99],
        ];
    }
}
