<?php

namespace Northwestern\SysDev\DynamicForms\Tests;

use Northwestern\SysDev\DynamicForms\Components\Inputs\Textfield;
use Northwestern\SysDev\DynamicForms\Components\Layout\Panel;
use Northwestern\SysDev\DynamicForms\Form;
use Tests\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Form
 */
class FormTest extends TestCase
{
    /**
     * @dataProvider definitionDataProvider
     * @covers ::setDefinition
     * @covers ::__construct
     * @covers ::setSubmission
     * @covers ::processComponentDefinition
     * @covers ::getCustomChildren
     * @covers ::flattenComponents
     */
    public function testSetDefinition(string $json, array $expectedComponents, $expectedFlatComponents): void
    {
        $form = new Form($json);
        $this->assertEquals($expectedComponents, $form->components());
        $this->assertEquals($expectedFlatComponents, $form->flatComponents());
    }

    public function definitionDataProvider(): array
    {
        $textfield = new Textfield('textField', 'Sample Field', [], ['required' => true], false, []);
        $panel = new Panel('page1', 'Page 1', [$textfield], [], false, ['title' => 'Panel Container', 'collapsible' => false]);

        return [
            // ["json string", [components], [flattened components]],
            'panel' => [
                '{"components":[{"title":"Panel Container","collapsible":false,"key":"page1","type":"panel","label":"Page 1","components":[{"label":"Sample Field","tableView":true,"validate":{"required":true},"key":"textField","type":"textfield","input":true}],"input":false,"tableView":false}]}',
                [$panel],
                ['page1' => $panel, 'textField' => $textfield],
            ],
        ];
    }

    /**
     * @covers ::validate
     * @dataProvider submissionDataProvider
     */
    public function testValidate(string $definition, string $submission, bool $passes, int $componentCount): void
    {
        $form = new Form($definition, $submission);
        $bag = $form->validate();

        $this->assertEquals($componentCount, count($form->flatComponents()));
        $this->assertEquals($passes, $bag->isEmpty());
    }

    public function submissionDataProvider(): array
    {
        $json = fn (string $filename) => file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'Fixtures'.DIRECTORY_SEPARATOR.$filename);

        return [
            'simple form validates' => [
                $json('simple_definition.json'),
                '{"textField": "a"}',
                true,
                1,
            ],
            'simple form fails' => [
                $json('simple_definition.json'),
                '{"textField": ""}',
                false,
                1,
            ],
            'complex form passes' => [
                $json('complex_definition.json'),
                $json('complex_submission.json'),
                true,
                19,
            ],
            'form with times passes' => [
                $json('time_definition.json'),
                $json('time_submission.json'),
                true,
                14,
            ],
            /*
            'form with conditional fields passes' => [
                $json('conditional_definition.json'),
                $json('conditional_submission.json'),
                true,
                5,
            ]
            */
        ];
    }
}
