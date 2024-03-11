<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\TestCases;

use function app;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\BaseComponent
 */
abstract class InputComponentTestCase extends BaseComponentTestCase
{
    /**
     * @covers ::canValidate
     */
    public function testCanValidate(): void
    {
        $this->assertTrue($this->getComponent()->canValidate());
    }

    /**
     * @covers ::processValidations
     * @covers ::validate
     * @dataProvider validationsProvider
     */
    public function testValidations(
        array $validations,
        mixed $submissionValue,
        bool $passes,
        ?string $message = null,
        array $additional = [],
        ?string $errorLabel = null
    ): void {
        $component = $this->getComponent(
            errorLabel: $errorLabel,
            validations: $validations,
            additional: $additional, // some components put validation fields in here
            submissionValue: $submissionValue,
        );

        $bag = $component->validate($component->key(), app()->make('validator'));
        $this->assertEquals($passes, $bag->isEmpty(), $bag);

        if ($message) {
            $this->assertEquals($message, $bag->first());
        }
    }

    /**
     * @covers ::processValidations
     * @covers ::validate
     * @dataProvider validationsProvider
     */
    public function testValidationsOnMultipleValues(
        array $validations,
        mixed $submissionValue,
        bool $passes,
        ?string $message = null,
        array $additional = [],
        ?string $errorLabel = null
    ) {
        $component = $this->getComponent(
            errorLabel: $errorLabel,
            validations: $validations,
            additional: $additional,
            hasMultipleValues: true,
            submissionValue: [$submissionValue], // wrap the value from the single-value provider w/ an array
        );

        $bag = $component->validate($component->key(), app()->make('validator'));
        $this->assertEquals($passes, $bag->isEmpty(), $bag);

        if ($message) {
            $this->assertEquals($message, $bag->first());
        }
    }

    /**
     * @covers ::processValidations
     * @covers ::validate
     */
    public function testValidationsOnMultipleValuesForNullSubmissionValue()
    {
        $component = $this->getComponent(
            hasMultipleValues: true,
            submissionValue: null,
        );

        $bag = $component->validate();
        $this->assertTrue($bag->isEmpty());
    }

    /**
     * @covers ::submissionValue
     * @dataProvider submissionValueProvider
     */
    public function testSubmissionValue(?string $case, mixed $submissionValue, mixed $expected): void
    {
        $component = $this->getComponent(case: $case ?? 'mixed');
        $component->setSubmissionValue($submissionValue);

        $this->assertEquals($expected, $component->submissionValue());
    }
}
