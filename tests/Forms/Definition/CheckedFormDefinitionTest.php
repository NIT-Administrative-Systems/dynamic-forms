<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Forms\Definition;

use Northwestern\SysDev\DynamicForms\Forms\Definition\CheckedFormDefinition;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssue;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssueSeverity;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssueType;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssueTypeInterface;
use Northwestern\SysDev\DynamicForms\Tests\Fixtures\Definition\CustomIssueType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CheckedFormDefinitionTest extends TestCase
{
    public function testEmpty(): void
    {
        $checked = new CheckedFormDefinition;

        $this->assertTrue($checked->isClean());
        $this->assertFalse($checked->hasErrors());
        $this->assertFalse($checked->hasWarnings());
        $this->assertSame([], $checked->all());
        $this->assertSame([], $checked->errors());
        $this->assertSame([], $checked->warnings());
    }

    public function testSeparatesErrorsFromWarnings(): void
    {
        $error = $this->issue('a', DefinitionIssueType::JsonValidationInvalidStructure);
        $warning = $this->issue('b', DefinitionIssueType::JsonValidationMissingTrue);

        $checked = new CheckedFormDefinition([$warning, $error]);

        $this->assertFalse($checked->isClean());
        $this->assertTrue($checked->hasErrors());
        $this->assertTrue($checked->hasWarnings());
        $this->assertSame([$warning, $error], $checked->all());
        $this->assertSame([$error], $checked->errors());
        $this->assertSame([$warning], $checked->warnings());
    }

    public function testOnlyWarnings(): void
    {
        $checked = new CheckedFormDefinition([$this->issue('a', DefinitionIssueType::JsonValidationMissingTrue)]);

        $this->assertFalse($checked->isClean());
        $this->assertFalse($checked->hasErrors());
        $this->assertTrue($checked->hasWarnings());
    }

    public function testMergeKeepsMultipleIssuesForTheSameComponent(): void
    {
        $first = $this->issue('same', DefinitionIssueType::JsonValidationInvalidStructure);
        $second = $this->issue('same', DefinitionIssueType::JsonValidationMissingTrue);

        $left = new CheckedFormDefinition([$first]);
        $right = new CheckedFormDefinition([$second]);
        $merged = $left->merge($right);

        $this->assertSame([$first, $second], $merged->all());
        $this->assertSame([$first], $left->all());
        $this->assertSame([$second], $right->all());
    }

    public function testNormalizesToList(): void
    {
        $issue = $this->issue('a', DefinitionIssueType::JsonValidationMissingTrue);

        $checked = new CheckedFormDefinition(['a' => $issue]);

        $this->assertSame([$issue], $checked->all());
    }

    public function testReturnedIssuesCannotMutateCollection(): void
    {
        $issue = $this->issue('a', DefinitionIssueType::JsonValidationMissingTrue);
        $checked = new CheckedFormDefinition([$issue]);

        $copy = $checked->all();
        unset($copy[0]);

        $this->assertSame([$issue], $checked->all());
    }

    public function testIssueProxiesSeverityAndDescription(): void
    {
        $issue = $this->issue('a', DefinitionIssueType::JsonValidationInvalidStructure);

        $this->assertSame(DefinitionIssueSeverity::Error, $issue->severity());
        $this->assertSame(DefinitionIssueType::JsonValidationInvalidStructure->description(), $issue->description());
    }

    public function testBuiltInIssueTypeCodeIsBackingValue(): void
    {
        $this->assertSame('json_validation_missing_true', DefinitionIssueType::JsonValidationMissingTrue->code());
    }

    public function testCustomIssueTypesAreSeparatedBySeverity(): void
    {
        $customError = $this->issue('a', CustomIssueType::AppError);
        $customWarning = $this->issue('b', CustomIssueType::AppWarning);
        $builtInWarning = $this->issue('c', DefinitionIssueType::JsonValidationMissingTrue);

        $checked = new CheckedFormDefinition([$customError, $customWarning, $builtInWarning]);

        $this->assertSame([$customError], $checked->errors());
        $this->assertSame([$customWarning, $builtInWarning], $checked->warnings());
        $this->assertSame('Application error.', $customError->description());
        $this->assertSame('app.app_error', $customError->type->code());
    }

    public function testFormIssue(): void
    {
        $issue = DefinitionIssue::forForm('components', CustomIssueType::AppError, 'Details.');

        $this->assertTrue($issue->isFormIssue());
        $this->assertNull($issue->componentKey);
        $this->assertNull($issue->componentLabel());
        $this->assertNull($issue->componentDisplayName());
        $this->assertSame('components', $issue->jsonPath);
        $this->assertSame(CustomIssueType::AppError, $issue->type);
        $this->assertSame('Details.', $issue->details);
    }

    public function testFormIssueIgnoresLabel(): void
    {
        $issue = new DefinitionIssue(
            componentKey: null,
            componentLabel: 'Stray label',
            jsonPath: 'components',
            type: CustomIssueType::AppError,
        );

        $this->assertTrue($issue->isFormIssue());
        $this->assertNull($issue->componentLabel());
        $this->assertNull($issue->componentDisplayName());
    }

    public function testComponentIssueIsNotFormIssue(): void
    {
        $this->assertFalse($this->issue('a', DefinitionIssueType::JsonValidationMissingTrue)->isFormIssue());
    }

    #[DataProvider('componentLabelProvider')]
    public function testIssueComponentLabel(?string $label, string $expectedLabel, string $expectedDisplayName): void
    {
        $issue = new DefinitionIssue(
            componentKey: 'class',
            componentLabel: $label,
            jsonPath: 'validate.json.if',
            type: DefinitionIssueType::JsonValidationMissingTrue,
        );

        $this->assertSame($expectedLabel, $issue->componentLabel());
        $this->assertSame($expectedDisplayName, $issue->componentDisplayName());
    }

    public static function componentLabelProvider(): array
    {
        return [
            'label' => ['Class Year', 'Class Year', 'Class Year (class)'],
            'null label falls back to key' => [null, 'class', 'class'],
            'empty label falls back to key' => ['', 'class', 'class'],
            'label same as key' => ['class', 'class', 'class'],
        ];
    }

    private function issue(string $key, DefinitionIssueTypeInterface $type): DefinitionIssue
    {
        return new DefinitionIssue(componentKey: $key, componentLabel: null, jsonPath: 'validate.json.if', type: $type);
    }
}
