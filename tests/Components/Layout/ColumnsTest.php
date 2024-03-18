<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Layout;

use Northwestern\SysDev\DynamicForms\Components\Layout\Columns;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\BaseComponentTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Layout\Columns
 */
final class ColumnsTest extends BaseComponentTestCase
{
    protected string $componentClass = Columns::class;

    #[DataProvider('pathsToChildrenDataProvider')]
    public function testPathsToChildren(array $component, array $expectedPaths): void
    {
        $this->assertEquals($expectedPaths, Columns::pathsToChildren($component));
    }

    public static function pathsToChildrenDataProvider(): array
    {
        return [
            // component, expected paths
            'no children' => [['columns' => []], []],
            'three columns' => [
                [
                    'columns' => [
                        [
                            'components' => [
                                ['dummy' => true],
                            ],
                            'width' => 4,
                            'offset' => 0,
                            'push' => 0,
                            'pull' => 0,
                            'size' => 'md',
                        ],
                        [
                            'components' => [
                                ['dummy' => true],
                                ['dummy' => true],
                            ],
                            'width' => 4,
                            'offset' => 0,
                            'push' => 0,
                            'pull' => 0,
                            'size' => 'md',
                        ],
                        [
                            'components' => [
                                ['dummy' => true],
                            ],
                            'width' => 4,
                            'offset' => 0,
                            'push' => 0,
                            'pull' => 0,
                            'size' => 'md',
                        ],
                    ],
                ],
                [
                    'columns.0',
                    'columns.1',
                    'columns.2',
                ],
            ],
        ];
    }
}
