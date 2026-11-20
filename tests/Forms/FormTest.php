<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Forms;

use Northwestern\SysDev\DynamicForms\ComponentRegistry;
use Northwestern\SysDev\DynamicForms\DefinitionCheckRegistry;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssue;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssueType;
use Northwestern\SysDev\DynamicForms\Forms\Form;
use Northwestern\SysDev\DynamicForms\Tests\Fixtures\Definition\CustomIssueType;
use Northwestern\SysDev\DynamicForms\Tests\Fixtures\Definition\DuplicateLabelCheck;
use Northwestern\SysDev\DynamicForms\Tests\Fixtures\Definition\FlaggedKeyCheck;
use Northwestern\SysDev\DynamicForms\Tests\Fixtures\Definition\RequiresEmailCheck;
use Northwestern\SysDev\DynamicForms\Tests\Fixtures\Definition\SelfCheckingTextfield;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class FormTest extends TestCase
{
    public function testCheckDefinitionForClassValidationInsideColumns(): void
    {
        $form = new Form(file_get_contents(__DIR__.'/../Fixtures/class_validation_definition.json'));

        $checked = $form->checkDefinition();

        $this->assertFalse($checked->isClean());
        $this->assertFalse($checked->hasErrors());
        $this->assertCount(1, $checked->warnings());
        $warning = $checked->warnings()[0];
        $this->assertSame('class', $warning->componentKey);
        $this->assertSame('Class', $warning->componentLabel());
        $this->assertSame('validate.json.if', $warning->jsonPath);
        $this->assertSame(DefinitionIssueType::JsonValidationMissingTrue, $warning->type);
        $this->assertEquals($checked->all(), $form->checkDefinition()->all());
    }

    public function testCheckDefinitionSeparatesErrorsFromWarnings(): void
    {
        $form = new Form(json_encode([
            'components' => [
                [
                    'key' => 'broken',
                    'type' => 'textfield',
                    'validate' => ['json' => ['if' => [true, 'Invalid']]],
                ],
                [
                    'key' => 'suspicious',
                    'type' => 'textfield',
                    'validate' => ['json' => ['if' => [true, 'Invalid', false]]],
                ],
            ],
        ], JSON_THROW_ON_ERROR));

        $checked = $form->checkDefinition();

        $this->assertTrue($checked->hasErrors());
        $this->assertTrue($checked->hasWarnings());
        $this->assertSame(['broken'], $this->componentKeys($checked->errors()));
        $this->assertSame(['suspicious'], $this->componentKeys($checked->warnings()));
        $this->assertSame(['broken', 'suspicious'], $this->componentKeys($checked->all()));
    }

    public function testCheckDefinitionIncludesConditionallyHiddenComponents(): void
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

        $this->assertSame(['hidden', 'other'], $this->componentKeys($form->checkDefinition()->all()));
    }

    public function testCheckDefinitionIsCleanWithoutJsonValidation(): void
    {
        $form = new Form(file_get_contents(__DIR__.'/../Fixtures/simple_definition.json'));

        $this->assertTrue($form->checkDefinition()->isClean());
        $this->assertSame([], $form->checkDefinition()->all());
    }

    public function testCheckDefinitionPreservesNumericComponentKeys(): void
    {
        $form = new Form(json_encode([
            'components' => [[
                'key' => '123',
                'type' => 'textfield',
                'validate' => ['json' => ['if' => [true, 'Invalid', false]]],
            ]],
        ], JSON_THROW_ON_ERROR));

        $this->assertSame('123', $form->checkDefinition()->all()[0]->componentKey);
    }

    public function testCheckDefinitionRunsComponentAndRegisteredChecks(): void
    {
        $components = new ComponentRegistry;
        $components->register(SelfCheckingTextfield::class);
        $this->app->instance(ComponentRegistry::class, $components);

        $checks = new DefinitionCheckRegistry;
        $checks->register(FlaggedKeyCheck::class);
        $checks->register(DuplicateLabelCheck::class);
        $checks->register(RequiresEmailCheck::class);
        $this->app->instance(DefinitionCheckRegistry::class, $checks);

        $form = new Form(json_encode(['components' => [
            ['key' => 'self', 'label' => 'Name', 'type' => SelfCheckingTextfield::TYPE],
            ['key' => 'flagged', 'type' => 'textfield', 'validate' => ['json' => ['if' => [true, 'Invalid']]]],
            ['key' => 'other', 'label' => 'Name', 'type' => 'textfield'],
        ]]));

        $issues = array_map(
            fn (DefinitionIssue $issue) => [$issue->componentKey, $issue->type],
            $form->checkDefinition()->all(),
        );

        $this->assertSame([
            ['self', CustomIssueType::AppWarning],
            ['flagged', DefinitionIssueType::JsonValidationInvalidStructure],
            ['flagged', CustomIssueType::AppWarning],
            ['other', CustomIssueType::AppError],
            [null, CustomIssueType::AppError],
        ], $issues);
    }

    /**
     * @param  list<DefinitionIssue>  $issues
     * @return list<string>
     */
    private function componentKeys(array $issues): array
    {
        return array_map(fn (DefinitionIssue $issue) => $issue->componentKey, $issues);
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
