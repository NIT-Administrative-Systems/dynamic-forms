<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Forms\Definition;

use Northwestern\SysDev\DynamicForms\Forms\Definition\CheckedFormDefinition;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssue;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssueSeverity;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssueType;
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

    private function issue(string $key, DefinitionIssueType $type): DefinitionIssue
    {
        return new DefinitionIssue(componentKey: $key, jsonPath: 'validate.json.if', type: $type);
    }
}
