<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components;

use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Tests\TestCase;

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
     */
    public function testGetters(): void
    {
        $ref = new \ReflectionClass($this->componentClass);
        $component = $this->getComponent();

        $this->assertEquals('test', $component->key());
        $this->assertEquals('Test', $component->label());
        $this->assertEquals($ref->getConstant('TYPE'), $component->type());
        $this->assertEquals([], $component->components());
        $this->assertFalse($component->hasMultipleValues());
        $this->assertFalse($component->hasConditional());
        $this->assertNull($component->conditional());
    }

    /**
     * Makes a component object based on the componentClass prop.
     *
     * @see ComponentInterface
     */
    protected function getComponent(
        string $key = 'test',
        ?string $label = 'Test',
        array $components = [],
        array $validations = [],
        ?array $additional = [],
        bool $hasMultipleValues = false,
        ?array $conditional = null,
        ?string $customConditional = null,
        mixed $submissionValue = null
    ): ComponentInterface {
        /** @var ComponentInterface $component */
        $component = new ($this->componentClass)(
            $key,
            $label,
            $components,
            $validations,
            $hasMultipleValues,
            $conditional,
            $customConditional,
            array_merge($this->defaultAdditional, $additional),
        );

        $component->setSubmissionValue($submissionValue);

        return $component;
    }
}
