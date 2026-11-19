<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Forms;

use Northwestern\SysDev\DynamicForms\Forms\FormWarningType;
use PHPUnit\Framework\TestCase;

final class FormWarningTypeTest extends TestCase
{
    public function testDescriptions(): void
    {
        $this->assertSame(
            'Custom JSON Logic validation must use an "if" array with exactly three arguments: a condition and two outputs.',
            FormWarningType::JsonValidationInvalidStructure->description(),
        );
        $this->assertSame(
            'Neither output of the JSON Logic "if" is true. This validation rule may never pass.',
            FormWarningType::JsonValidationMissingTrue->description(),
        );
    }
}
