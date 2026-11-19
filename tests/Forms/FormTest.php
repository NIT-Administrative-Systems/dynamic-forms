<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Forms;

use Northwestern\SysDev\DynamicForms\Forms\Form;
use Northwestern\SysDev\DynamicForms\Forms\FormWarningType;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class FormTest extends TestCase
{
    public function testWarningsForClassValidationInsideColumns(): void
    {
        $form = new Form(file_get_contents(__DIR__.'/../Fixtures/class_validation_definition.json'));

        $warnings = $form->warnings();

        $this->assertFalse($warnings->isEmpty());
        $this->assertSame(['class'], array_keys($warnings->all()));
        $warning = $warnings->all()['class'];
        $this->assertSame('class', $warning->componentKey);
        $this->assertSame('validate.json.if', $warning->jsonPath);
        $this->assertSame(FormWarningType::JsonValidationMissingTrue, $warning->type);
        $this->assertEquals($warnings->all(), $form->warnings()->all());
    }

    public function testWarningsIncludeConditionallyHiddenComponents(): void
    {
        $component = [
            'key' => 'hidden',
            'type' => 'textfield',
            'conditional' => ['when' => 'toggle', 'eq' => 'yes', 'show' => true],
            'validate' => ['json' => ['if' => [true, 'Invalid', false]]],
        ];
        $form = new Form(json_encode([
            'components' => [
                $component,
                array_replace($component, ['key' => 'other']),
                ['key' => 'ordinary', 'type' => 'textfield'],
            ],
        ], JSON_THROW_ON_ERROR));

        $this->assertSame(['hidden', 'other'], array_keys($form->warnings()->all()));
    }

    public function testWarningsAreEmptyWithoutJsonValidation(): void
    {
        $form = new Form(file_get_contents(__DIR__.'/../Fixtures/simple_definition.json'));

        $this->assertTrue($form->warnings()->isEmpty());
        $this->assertSame([], $form->warnings()->all());
    }

    public function testWarningsPreserveNumericComponentKeys(): void
    {
        $form = new Form(json_encode([
            'components' => [[
                'key' => '123',
                'type' => 'textfield',
                'validate' => ['json' => ['if' => [true, 'Invalid', false]]],
            ]],
        ], JSON_THROW_ON_ERROR));

        $this->assertSame('123', $form->warnings()->all()['123']->componentKey);
    }

    #[DataProvider('formDataProvider')]
    public function testFormDeserialization(string $definition, int $componentCount): void
    {
        $form = new Form($definition);

        $this->assertEquals($componentCount, count($form->flatComponents()));
    }

    public static function formDataProvider(): array
    {
        $json = fn (string $filename) => file_get_contents(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'Fixtures', $filename]));

        return [
            'simple form' => [
                $json('simple_definition.json'),
                1,
            ],
            'complex form' => [
                $json('complex_definition.json'),
                19,
            ],
            'form with time' => [
                $json('time_definition.json'),
                14,
            ],
            'form with conditional fields' => [
                $json('conditional_definition.json'),
                5,
            ],
        ];
    }
}
