<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Forms;

use Northwestern\SysDev\DynamicForms\Forms\FormWarning;
use Northwestern\SysDev\DynamicForms\Forms\FormWarnings;
use Northwestern\SysDev\DynamicForms\Forms\FormWarningType;
use PHPUnit\Framework\TestCase;

final class FormWarningsTest extends TestCase
{
    public function testEmptyWarnings(): void
    {
        $warnings = new FormWarnings;

        $this->assertTrue($warnings->isEmpty());
        $this->assertSame([], $warnings->all());
    }

    public function testReturnedWarningsCannotMutateCollection(): void
    {
        $warning = new FormWarning(
            componentKey: 'class',
            jsonPath: 'validate.json.if',
            type: FormWarningType::JsonValidationMissingTrue,
            details: 'Neither output is literal true.',
        );
        $warnings = new FormWarnings(['class' => $warning]);

        $copy = $warnings->all();
        unset($copy['class']);

        $this->assertFalse($warnings->isEmpty());
        $this->assertSame(['class' => $warning], $warnings->all());
    }
}
