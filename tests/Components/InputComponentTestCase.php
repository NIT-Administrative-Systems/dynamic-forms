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
            submissionValue: $submissionValue,
            additional: $additional, // some components put validation fields in here
        );

        $bag = $component->validate($component->key(), app()->make('validator'));
        $this->assertEquals($passes, $bag->isEmpty(), $bag);

        /*
        if ($message) {

        }
        dump($bag);
         */
    }
}
