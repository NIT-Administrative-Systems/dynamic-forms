<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\TestCases;

use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Northwestern\SysDev\DynamicForms\Forms\FormWarningType;
use Orchestra\Testbench\TestCase;

abstract class BaseComponentTestCase extends TestCase
{
    protected string $componentClass;
    protected array $defaultAdditional = [];

    public function testConstruct(): void
    {
        $this->assertInstanceOf($this->componentClass, $this->getComponent());
    }

    public function testCanValidate(): void
    {
        $this->assertFalse($this->getComponent()->canValidate());
    }

    public function testGetters(): void
    {
        $ref = new \ReflectionClass($this->componentClass);
        $component = $this->getComponent(additional: ['disabled' => true], defaultValue: 'foo');

        $this->assertEquals('test', $component->key());
        $this->assertEquals('Test', $component->label());
        $this->assertEquals($ref->getConstant('TYPE'), $component->type());
        $this->assertEquals([], $component->components());
        $this->assertFalse($component->hasMultipleValues());
        $this->assertFalse($component->hasConditional());
        $this->assertNull($component->conditional());
        $this->assertNull($component->errorLabel());
        $this->assertFalse($component->isCalculated());
        $this->assertNull($component->calculation());
        $this->assertEquals('foo', $component->defaultValue());
        $this->assertNull($component->validation('required'));
        $this->assertEmpty($component->validations());
        $this->assertNull($component->advancedValidations());
        $this->assertTrue($component->additional('disabled'));
    }

    public function testWarningsWithoutAdvancedValidation(): void
    {
        $warnings = $this->getComponent()->warnings();

        $this->assertTrue($warnings->isEmpty());
        $this->assertSame([], $warnings->all());
    }

    public function testWarningsForJsonValidationMissingTrue(): void
    {
        $component = $this->getComponent(validations: [
            'json' => ['if' => [true, 'Invalid', false]],
        ]);

        $warnings = $component->warnings()->all();

        $this->assertSame(['test'], array_keys($warnings));
        $this->assertSame('test', $warnings['test']->componentKey);
        $this->assertSame('validate.json.if', $warnings['test']->jsonPath);
        $this->assertSame(FormWarningType::JsonValidationMissingTrue, $warnings['test']->type);
    }

    public function testWarningsForJsonValidationWithTrue(): void
    {
        $component = $this->getComponent(validations: [
            'json' => ['if' => [true, true, 'Invalid']],
        ]);

        $this->assertTrue($component->warnings()->isEmpty());
    }

    /**
     * Makes a component object based on the componentClass prop.
     *
     * @see ComponentInterface
     */
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
        ?array $calculateValue = null,
        mixed $defaultValue = null,
        mixed $submissionValue = null
    ): ComponentInterface {
        /** @var ComponentInterface $component */
        $component = new ($this->componentClass)(
            $key,
            $label,
            $errorLabel,
            $components,
            $validations,
            $hasMultipleValues,
            $conditional,
            $customConditional,
            $case,
            $calculateValue,
            $defaultValue,
            array_merge($this->defaultAdditional, $additional),
        );

        $component->setSubmissionValue($submissionValue);

        return $component;
    }
}
