<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Forms;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Textfield;
use Northwestern\SysDev\DynamicForms\Forms\Form;
use Northwestern\SysDev\DynamicForms\Forms\ValidatedForm;
use Tests\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Forms\ValidatedForm
 */
class ValidatedFormTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::messages
     * @covers ::isValid
     * @covers ::values
     * @covers ::validatableComponents
     */
    public function testGetters(): void
    {
        $components = [
            'testField' => new Textfield(
                'testField',
                'Test Field',
                null,
                [],
                ['required' => true],
                false,
                null,
                null,
                'mixed',
                [],
            ),
        ];

        $values = ['testField' => 'Hello'];

        $validatedForm = new ValidatedForm($components, $values);

        $this->assertTrue($validatedForm->isValid());
        $this->assertEmpty($validatedForm->messages());
        $this->assertEquals($values, $validatedForm->values());
    }

    /**
     * @covers ::__construct
     * @covers ::validatableComponents
     * @dataProvider  validationDataProvider
     */
    public function testValidation(array $flatComponents, array $submission, bool $passes, array $expectedValues): void
    {
        $validatedForm = new ValidatedForm($flatComponents, $submission);

        $this->assertEquals($passes, $validatedForm->isValid());
    }

    public function validationDataProvider(): array
    {
        $json = fn (string $filename) => file_get_contents(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'Fixtures', $filename]));
        $components = fn (string $filename) => (new Form($json($filename)))->flatComponents();
        $values = fn (string $filename) => json_decode($json($filename), true);

        return [
            'simple form passes' => [
                $components('simple_definition.json'),
                ['textField' => 'A'],
                true,
                ['textField' => 'A'],
            ],
            'simple form fails' => [
                $components('simple_definition.json'),
                ['textField' => ''],
                false,
                ['textField' => ''],
            ],
            'complex form passes' => [
                $components('complex_definition.json'),
                $values('complex_submission.json'),
                true,
                $values('complex_submission.json'),
            ],
            'form with times passes' => [
                $components('time_definition.json'),
                $values('time_submission.json'),
                true,
                $values('time_submission.json'),
            ],
            'form with conditional fields passes' => [
                $components('conditional_definition.json'),
                $values('conditional_submission.json'),
                true,
                $values('conditional_submission.json'),
            ],
            'strips extra values' => [
                $components('simple_definition.json'),
                ['textField' => 'A', 'unknownField' => 'B'],
                true,
                ['textField' => 'A'],
            ],
            'strips hidden conditional field from user data' => [
                $components('conditional_definition.json'),
                array_merge($values('conditional_submission.json'), ['textField1' => 'hey']),
                true,
                $values('conditional_submission.json'),
            ],
            'transformations' => [
                ['test' => new Textfield('test', 'Test', null, [], [], false, null, null, CaseEnum::UPPER, [])],
                ['test' => 'lowercase'],
                true,
                ['test' => 'LOWERCASE'],
            ],
        ];
    }
}
