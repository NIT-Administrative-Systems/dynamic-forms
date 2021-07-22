<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Illuminate\Support\Arr;
use Northwestern\SysDev\DynamicForms\Components\Inputs\File;
use Northwestern\SysDev\DynamicForms\Storage\S3Driver;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\File
 */
class FileTest extends InputComponentTestCase
{
    protected string $componentClass = File::class;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['router']
            ->get('/dynamic-forms/storage/s3/{fileKey}')
            ->name('dynamic-forms.S3-file-redirect');
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
        $submissionValue = $this->resolveUrlForSubmissionValue($submissionValue);

        parent::testValidations($validations, $submissionValue, $passes, $message, $additional, $errorLabel);
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
            submissionValue: $this->resolveUrlForSubmissionValue($submissionValue), // no need to wrap for file object
        );

        $bag = $component->validate($component->key(), app()->make('validator'));
        $this->assertEquals($passes, $bag->isEmpty(), $bag);

        if ($message) {
            $this->assertEquals($message, $bag->first());
        }
    }

    /**
     * Resolves callables for the submissionValue's URLs.
     *
     * This is using the URL helper, which isn't available in the data provider method because it's running before
     * the TestCase's setUp() method, which bootstraps the framework.
     */
    protected function resolveUrlForSubmissionValue(mixed $submissionValue): mixed
    {
        $url = Arr::get($submissionValue, '0.url');
        if (is_callable($url)) {
            Arr::set($submissionValue, '0.url', $url());
        }

        return $submissionValue;
    }

    public function validationsProvider(): array
    {
        $filePASS = json_decode('[{
            "storage": "s3",
            "name": "TEST.docx",
            "key": "TEST.docx",
            "size": 10000,
            "type": "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "originalName": "TEST.docx"
        }]',true);

        $filePASS[0]['url'] = fn () => route('dynamic-forms.S3-file-redirect', [$filePASS[0]['name']]);
        $fileNameCheckFail = $filePASS;
        $fileNameCheckFail[0]['name'] = 'TEST2.docx';
        $fileKeyCheckFail = $filePASS;
        $fileKeyCheckFail[0]['key'] = 'TEST2.docx';
        $fileURLCheckFail = $filePASS;
        $fileURLCheckFail[0]['url'] = 'TEST2.docx';
        $fileNotFoundCheckFail = $filePASS;
        $fileNotFoundCheckFail[0]['name'] = 'TEST2.docx';
        $fileNotFoundCheckFail[0]['key'] = 'TEST2.docx';
        $fileNotFoundCheckFail[0]['url'] = 'TEST2.docx';

        return [
            'passes when no value is supplied' => [[], [], true],
            'invalid formatted file fails' => [[], [['storage' => 's3', 'dog' => 1]], false],
            'valid file passes' => [[], $filePASS, true],
            'required passes' => [['required' => true], $filePASS, true],
            'required fails' => [['required' => true], [], false],
            'FileExists fails from name consistency check' => [[], $fileNameCheckFail, false],
            'FileExists fails from key consistency check' => [[], $fileKeyCheckFail, false],
            'FileExists fails from url consistency check' => [[], $fileURLCheckFail, false],
            'FileExists fails from file not found ' => [[], $fileNotFoundCheckFail, false],
            'FileExists passes' => [[], $filePASS, true],
        ];
    }

    public function submissionValueProvider(): array
    {
        return [
            'empty passes through' => [null, [], []],
        ];
    }

    protected function getComponent(
        string $key = 'test',
        ?string $label = 'Test',
        ?string $errorLabel = null,
        array $components = [],
        array $validations = [],
        ?array $additional = [],
        bool $hasMultipleValues = false,
        ?array $conditional = null,
        ?string $customConditional = null,
        string $case = 'mixed',
        mixed $submissionValue = null
    ): File {
        /** @var File $component */
        $component = parent::getComponent(
            $key,
            $label,
            $errorLabel,
            $components,
            $validations,
            $additional,
            $hasMultipleValues,
            $conditional,
            $customConditional,
            $case,
            $submissionValue
        );

        $stub = $this->createPartialMock(S3Driver::class, ['findObject']);
        $map = [
            ['TEST.docx', true],
            ['TEST2.docx', false],
        ];

        $stub->method('findObject')->willReturn(true);

        $component->setStorageDriver($stub);

        $component->setSubmissionValue($submissionValue);

        return $component;
    }
}
