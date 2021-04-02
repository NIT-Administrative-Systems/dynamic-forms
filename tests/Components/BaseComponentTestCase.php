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
     */
    public function testGetters(): void
    {
        $ref = new \ReflectionClass($this->componentClass);
        $component = $this->getComponent();

        $this->assertEquals($component->key(), 'test');
        $this->assertEquals($component->label(), 'Test');
        $this->assertEquals($component->type(), $ref->getConstant('TYPE'));
        $this->assertEquals($component->components(), []);
        $this->assertEquals($component->hasMultipleValues(), false);
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
        mixed $submissionValue = null
    ): ComponentInterface {
        /** @var ComponentInterface $component */
        $component = new ($this->componentClass)($key, $label, $components, $validations, $hasMultipleValues, array_merge($this->defaultAdditional, $additional));
        $component->setSubmissionValue($submissionValue);

        return $component;
    }
}
