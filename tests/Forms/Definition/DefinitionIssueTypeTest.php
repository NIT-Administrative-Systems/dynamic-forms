<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Forms\Definition;

use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssueSeverity;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssueType;
use PHPUnit\Framework\TestCase;

final class DefinitionIssueTypeTest extends TestCase
{
    public function testDescriptions(): void
    {
        $this->assertSame(
            'Custom JSON Logic validation must use an "if" array with exactly three arguments: a condition and two outputs.',
            DefinitionIssueType::JsonValidationInvalidStructure->description(),
        );
        $this->assertSame(
            'Neither output of the JSON Logic "if" is true. This validation rule may never pass.',
            DefinitionIssueType::JsonValidationMissingTrue->description(),
        );
    }

    public function testSeverities(): void
    {
        $this->assertSame(DefinitionIssueSeverity::Error, DefinitionIssueType::JsonValidationInvalidStructure->severity());
        $this->assertSame(DefinitionIssueSeverity::Warning, DefinitionIssueType::JsonValidationMissingTrue->severity());
    }
}
