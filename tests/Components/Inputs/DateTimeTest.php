<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Illuminate\Support\Carbon;
use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\DateTime;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\InputComponentTestCase;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\DateTime
 */
final class DateTimeTest extends InputComponentTestCase
{
    protected string $componentClass = DateTime::class;

    /**
     *           ["garbage"].
     */
    #[TestWith('[""]
["garbage"]')]
    public function testSubmissionValueHandlesNulls(string $value): void
    {
        $date = $this->getComponent(submissionValue: $value);
        $this->assertNull($date->submissionValue());
    }

    /**
     * @covers ::submissionValue
     */
    public function testSubmissionValueHandlesDates(): void
    {
        $date = $this->getComponent(submissionValue: '2021-03-25T12:00:00-05:00');
        $this->assertEquals('2021-03-25 17:00:00', $date->submissionValue());
    }

    public function validationsProvider(): array
    {
        return [
            'passes with blank' => [[], '', true, null],
            'passes with valid date' => [[], '2021-03-25T12:00:00-05:00', true, null],
            'fails with bad date' => [['required' => true], 'garbage', false, null],
            'required passes' => [['required' => true], '2021-03-25T12:00:00-05:00', true, null],
            'required fails' => [['required' => true], '', false, null],
            'passes with blank when week days & ends disabled' => [[], '', true, null, ['datePicker' => ['disableWeekends' => true, 'disableWeekDays' => true]]],
            'passes when weekdays disabled' => [[], '2021-03-27 12:00:00', true, null, ['datePicker' => ['disableWeekdays' => true]]],
            'fails when weekdays disabled' => [[], '2021-03-26 12:00:00', false, null, ['datePicker' => ['disableWeekdays' => true]]],
            'passes when weekends disabled' => [[], '2021-03-26 12:00:00', true, null, ['datePicker' => ['disableWeekends' => true]]],
            'fails when weekends disabled' => [[], '2021-03-27 12:00:00', false, null, ['datePicker' => ['disableWeekends' => true]]],
        ];
    }

    public function submissionValueProvider(): array
    {
        return [
            'no transformations' => [null, '2021-03-25T12:00:00-05:00', Carbon::parse('2021-03-25T12:00:00-05:00')],
            'upper' => [CaseEnum::UPPER, '2021-03-25T12:00:00-05:00', Carbon::parse('2021-03-25T12:00:00-05:00')],
            'lower' => [CaseEnum::LOWER, '2021-03-25T12:00:00-05:00', Carbon::parse('2021-03-25T12:00:00-05:00')],
        ];
    }
}
