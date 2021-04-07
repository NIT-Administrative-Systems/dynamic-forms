<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\BaseComponent
 */
class InputComponentTestCase extends BaseComponentTestCase
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
    public function testValidations(array $validations, mixed $submissionValue, bool $passes, ?string $message = null, array $additional = []): void
    {
        $component = $this->getComponent(
            validations: $validations,
            additional: $additional,  // some components put validation fields in here
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
    public function testValidationsOnMultipleValues(array $validations, mixed $submissionValue, bool $passes, ?string $message = null, array $additional = [])
    {
        $component = $this->getComponent(
            validations: $validations,
            additional: $additional,
            hasMultipleValues: true,
            submissionValue: [$submissionValue], // wrap the value from the single-value provider w/ an array
        );

        $bag = $component->validate($component->key(), app()->make('validator'));
        $this->assertEquals($passes, $bag->isEmpty(), $bag);
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
