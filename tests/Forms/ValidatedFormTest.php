<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Forms;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Textfield;
use Northwestern\SysDev\DynamicForms\Forms\Form;
use Northwestern\SysDev\DynamicForms\Forms\ValidatedForm;
use Orchestra\Testbench\TestCase;

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
     * @covers ::getMessageBag
     * @covers ::validated
     * @covers ::fails
     * @covers ::failed
     * @covers ::errors
     */
    public function testInterfaceGetters(): void
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

        $this->assertEmpty($validatedForm->getMessageBag());
        $this->assertEquals($values, $validatedForm->validated());
        $this->assertFalse($validatedForm->fails());
        $this->assertEmpty($validatedForm->failed());
        $this->assertTrue($validatedForm->errors()->isEmpty());
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
        $this->assertEquals($expectedValues, $validatedForm->values());
    }

    public function validationDataProvider(): array
    {
        $json = fn (string $filename) => file_get_contents(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'Fixtures', $filename]));
        $components = fn (string $filename) => (new Form($json($filename)))->flatComponents();
        $values = fn (string $filename) => json_decode($json($filename), true);
        $values2 = function (string $filename) use ($values) {
            $value = $values($filename);
            unset($value['submit']);
            return $value;
        };

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
                $values2('complex_submission.json'),
            ],
            'form with times passes' => [
                $components('time_definition.json'),
                $values('time_submission.json'),
                true,
                $values2('time_submission.json'),
            ],
            'form with conditional fields passes' => [
                $components('conditional_definition.json'),
                $values('conditional_submission.json'),
                true,
                $values2('conditional_submission.json'),
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
                $values2('conditional_submission.json'),
            ],
            'transformations' => [
                ['test' => new Textfield('test', 'Test', null, [], [], false, null, null, CaseEnum::UPPER, [])],
                ['test' => 'lowercase'],
                true,
                ['test' => 'LOWERCASE'],
            ],
        ];
    }

    /**
     * @covers ::allFiles
     * @dataProvider  filesDataProvider
     */
    public function testallFiles(array $flatComponents, array $submission, array $expectedAllfiles): void
    {
        $validatedForm = new ValidatedForm($flatComponents, $submission);
        $this->assertEquals($expectedAllfiles, $validatedForm->allFiles());
    }

    public function filesDataProvider(): array
    {
        $json = fn (string $filename) => file_get_contents(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'Fixtures', $filename]));
        $components = fn (string $filename) => (new Form($json($filename)))->flatComponents();
        $values = fn (string $filename) => json_decode($json($filename), true);

        return [
            'allFiles files' => [
                $components('file_definition.json'),
                $values('file_submission.json'),
                [["TEST26-a1d3ce37-c09f-411b-a5e0-58bc4251f489.pdf","TEST26.pdf"],["TEST27-f4982cc1-2395-4f14-9d3d-02864fc2a1ff.pdf","TEST27.pdf"],["TEST28-9ccd7055-782f-4b73-b193-f9ca703013f9.pdf","TEST28.pdf"],["TEST31-1702f647-2375-4a56-9dfa-8683fbc46da3.pdf","TEST31.pdf"],["TEST100-22fffc30-7d24-44bd-aeb4-1dd48aa975e5.pdf","TEST100.pdf"],["TEST99-ae05e075-7b5e-48f3-a495-177fad1ecfd8.pdf","TEST99.pdf"]],
            ],
        ];
    }
}
