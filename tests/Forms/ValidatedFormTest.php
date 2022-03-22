<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Forms;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Textfield;
use Northwestern\SysDev\DynamicForms\Forms\Form;
use Northwestern\SysDev\DynamicForms\Forms\ValidatedForm;
use Northwestern\SysDev\DynamicForms\Storage\S3Driver;
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
                null,
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
                null,
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

        // Values will be a Carbon object, so turn that into a string that matches the JSON file.
        $values = collect($validatedForm->values())->map(function (mixed $value, string $key) {
            if ($value instanceof CarbonInterface) {
                /** @var $value CarbonInterface */
                return $value->tz('America/Chicago')->toIso8601String();
            }

            return $value;
        });
        $this->assertEquals($expectedValues, $values->all());
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
                ['test' => new Textfield('test', 'Test', null, [], [], false, null, null, CaseEnum::UPPER, null, [])],
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
    public function testAllFiles(callable $flatComponents, array $submission, array $expectedAllFiles): void
    {
        $this->app->singleton(S3Driver::class, function ($app) {
            $mock = $this->createStub(S3Driver::class);

            $mock->method('findObject')->willReturn(true);

            return $mock;
        });

        /**
         * Resolve this to components. Form is going to need the S3Driver from the service container,
         * which is why we need to run it here and not in the dataProvider method.
         */
        $flatComponents = $flatComponents();

        $validatedForm = new ValidatedForm($flatComponents, $submission);
        $allFilesActual = collect($validatedForm->allFiles())
            ->map(fn ($file) => ['key' => $file['key'], 'originalName' => $file['originalName']]);

        $this->assertEquals($expectedAllFiles, $allFilesActual->all());
    }

    public function filesDataProvider(): array
    {
        $json = fn (string $filename) => file_get_contents(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'Fixtures', $filename]));
        $components = fn (string $filename) => (new Form($json($filename)))->flatComponents();
        $values = fn (string $filename) => json_decode($json($filename), true);

        return [
            'allFiles files' => [
                fn () => $components('file_definition.json'),
                $values('file_submission.json'),
                [
                    ['key' => 'TEST26-a1d3ce37-c09f-411b-a5e0-58bc4251f489.pdf', 'originalName' => 'TEST26.pdf'],
                    ['key' => 'TEST27-f4982cc1-2395-4f14-9d3d-02864fc2a1ff.pdf', 'originalName' => 'TEST27.pdf'],
                    ['key' => 'TEST28-9ccd7055-782f-4b73-b193-f9ca703013f9.pdf', 'originalName' => 'TEST28.pdf'],
                    ['key' => 'TEST31-1702f647-2375-4a56-9dfa-8683fbc46da3.pdf', 'originalName' => 'TEST31.pdf'],
                    ['key' => 'TEST100-22fffc30-7d24-44bd-aeb4-1dd48aa975e5.pdf', 'originalName' => 'TEST100.pdf'],
                    ['key' => 'TEST99-ae05e075-7b5e-48f3-a495-177fad1ecfd8.pdf', 'originalName' => 'TEST99.pdf'],
                ],
            ],
        ];
    }
}
