<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\TestCases;

use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\BaseComponent
 */
abstract class BaseComponentTestCase extends TestCase
{
    protected string $componentClass;
    protected array $defaultAdditional = [];

    /**
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf($this->componentClass, $this->getComponent());
    }

    /**
     * @covers ::canValidate
     */
    public function testCanValidate(): void
    {
        $this->assertFalse($this->getComponent()->canValidate());
    }

    /**
     * @covers ::key
     * @covers ::label
     * @covers ::type
     * @covers ::components
     * @covers ::hasMultipleValues
     * @covers ::hasConditional
     * @covers ::conditional
     * @covers ::errorLabel
     * @covers ::isCalculated
     * @covers ::calculation
     * @covers ::defaultValue
     * @covers ::validation
     * @covers ::validations
     */
    public function testGetters(): void
    {
        $ref = new \ReflectionClass($this->componentClass);
        $component = $this->getComponent(defaultValue: 'foo');

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
