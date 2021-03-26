<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Layout;

use Northwestern\SysDev\DynamicForms\Components\Layout\Table;
use Northwestern\SysDev\DynamicForms\Tests\Components\BaseComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Layout\Table
 */
class TableTest extends BaseComponentTestCase
{
    protected string $componentClass = Table::class;

    /**
     * @covers ::pathsToChildren
     */
    public function testPathsToChildren(): void
    {
        /** @var Table $table */
        $table = $this->getComponent();
        $paths = $table->pathsToChildren([
            'rows' => [
                [
                    [
                        ['components' => []],
                    ],
                    [
                        ['components' => []],
                    ],
                ],
                [
                    [
                        ['components' => []],
                    ],
                    [
                        ['components' => []],
                    ],
                ],
            ],
        ]);

        $this->assertEquals([
            'rows.0.0',
            'rows.0.1',
            'rows.1.0',
            'rows.1.1',
        ], $paths);
    }
}
