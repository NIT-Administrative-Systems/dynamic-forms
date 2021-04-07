<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Forms;

use Northwestern\SysDev\DynamicForms\Forms\Form;
use Tests\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Forms\Form
 */
class FormTest extends TestCase
{
    /**
     * @dataProvider formDataProvider
     * @covers ::setDefinition
     * @covers ::__construct
     * @covers ::processComponentDefinition
     * @covers ::getCustomChildren
     * @covers ::flattenComponents
     */
    public function testFormDeserialization(string $definition, int $componentCount): void
    {
        $form = new Form($definition);

        $this->assertEquals($componentCount, count($form->flatComponents()));
    }

    public function formDataProvider(): array
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
