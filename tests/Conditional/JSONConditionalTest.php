<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Conditional;

use Northwestern\SysDev\DynamicForms\Conditional\JSONConditional;
use Northwestern\SysDev\DynamicForms\JSONLogicInitHelper;
use Orchestra\Testbench\TestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Conditional\JSONConditional
 */
class JSONConditionalTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::__invoke
     * @covers \Northwestern\SysDev\DynamicForms\JSONLogic\LodashFunctions\___
     * @covers \Northwestern\SysDev\DynamicForms\JSONLogic\LodashFunctions\Arrays
     * @covers \Northwestern\SysDev\DynamicForms\JSONLogic\LodashFunctions\Collection
     * @covers \Northwestern\SysDev\DynamicForms\JSONLogic\LodashFunctions\Lang
     * @covers \Northwestern\SysDev\DynamicForms\JSONLogic\LodashFunctions\Math
     * @covers \Northwestern\SysDev\DynamicForms\JSONLogic\LodashFunctions\Objects
     * @covers \Northwestern\SysDev\DynamicForms\JSONLogic\LodashFunctions\Util
     * @covers \Northwestern\SysDev\DynamicForms\JSONLogicInitHelper
     * @dataProvider invokeDataProvider
     */
    public function testInvoke(array $jsonLogic, array $submissionValues, bool $expected): void
    {
        new JSONLogicInitHelper();
        $condition = new JSONConditional($jsonLogic);

        $this->assertEquals($expected, $condition($submissionValues));
    }

    public function invokeDataProvider(): array
    {
        return [
            //Basic JSONLOGIC
            'should show' => [
                'jsonLogic' => json_decode('{
                  "===": [
                    {
                      "var": "data.textField1"
                    },
                    "Show"
                  ]
                }', true),
                'submissionValues' => ['textField1' => 'Show'],
                'expected' => true,
            ],
            'should not show' => [
                'jsonLogic' => json_decode('{
                  "===": [
                    {
                      "var": "data.textField1"
                    },
                    "Show"
                  ]
                }', true),
                'submissionValues' => ['textField1' => 'Not Show'],
                'expected' => false,
            ],
            //isEqual (since used for most other tests)
            'should show equal' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    [
                      [
                        1,
                        2,
                        3
                      ]
                    ],
                    [
                      [
                        1,
                        2,
                        3
                      ]
                    ]
                  ]
                }', true),
                'submissionValues' => ['textField1' => 'Not Show'],
                'expected' => true,
            ],
            //Array functions
            'should show chunk' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_chunk": [
                        [
                          5,
                          7,
                          9,
                          10
                        ],
                        3
                      ]
                    },
                    [
                      [
                        5,
                        7,
                        9
                      ],
                      [10]
                    ]
                  ]
                }', true),
                'submissionValues' => [],
                'expected' => true,
            ],
            'should show chunk 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_chunk": [
                        [
                          5,
                          7,
                          9
                        ],
                        3
                      ]
                    },
                    [
                      [
                        5,
                        7,
                        9
                      ]
                    ]
                  ]
                }', true),
                'submissionValues' => [],
                'expected' => true,
            ],
            'should show compact' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_compact": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [0, 1, false, 2, '', 3],
                    'output' => [1, 2, 3], ],
                'expected' => true,
            ],
            'should show concat' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_concat": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    },
                        {
                      "var": "data.input4"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1],
                    'input2' => 2,
                    'input3' => [3],
                    'input4' => [[4]],
                    'output' => [1, 2, 3, [4]], ],
                'expected' => true,
            ],
            'should show difference' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_difference": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [2, 1],
                    'input2' => [2, 3],
                    'output' => [1], ],
                'expected' => true,
            ],
            'should show drop' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_drop": [
                        [
                          5,
                          7,
                          9,
                          10
                        ],
                        3
                      ]
                    },
                    [
                      10
                    ]
                  ]
                }', true),
                'submissionValues' => [],
                'expected' => true,
            ],
            'should show dropRight' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_dropRight": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'output' => [1, 2],
                ],
                'expected' => true,
            ],
            'should show findIndex' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_findIndex": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['user' => 'barney', 'age' => 36, 'active' => true], ['user' => 'fred',   'age' => 40, 'active' => false], ['user' => 'pebbles',   'age' => 41, 'active' => true]],
                    'input2' => ['active', false],
                    'output' => 1,
                ],
                'expected' => true,
            ],
            'should show findIndex 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_findIndex": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['user' => 'barney', 'age' => 36, 'active' => true], ['user' => 'fred',   'age' => 40, 'active' => false], ['user' => 'pebbles',   'age' => 41, 'active' => true]],
                    'input2' => ['user' => 'fred',   'age' => 40, 'active' => false],
                    'output' => 1,
                ],
                'expected' => true,
            ],
            'should show findLastIndex' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_findLastIndex": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['user' => 'barney', 'age' => 36, 'active' => true], ['user' => 'fred',   'age' => 40, 'active' => false], ['user' => 'pebbles',   'age' => 41, 'active' => true]],
                    'input2' => ['active', true],
                    'output' => 2,
                ],
                'expected' => true,
            ],
            'should show first' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_first": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'output' => 1,
                ],
                'expected' => true,
            ],
            'should show first 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_first": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [],
                    'output' => null,
                ],
                'expected' => true,
            ],
            'should show flatten' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_flatten": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, [2, [3, [4]], 5]],
                    'output' => [1, 2, [3, [4]], 5],
                ],
                'expected' => true,
            ],
            'should show flattenDeep' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_flattenDeep": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, [2, [3, [4]], 5]],
                    'output' => [1, 2, 3, 4, 5],
                ],
                'expected' => true,
            ],
            'should show flattenDepth' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_flattenDepth": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, [2, [3, [4]], 5]],
                    'input2' => 2,
                    'output' => [1, 2, 3, [4], 5],
                ],
                'expected' => true,
            ],
            'should show fromPairs' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_fromPairs": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['a', 1], ['b', 2]],
                    'output' => json_decode('{"a":1, "b":2}', true),
                ],
                'expected' => true,
            ],
            'should show head' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_head": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'output' => 1,
                ],
                'expected' => true,
            ],
            'should show head 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_head": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [],
                    'output' => null,
                ],
                'expected' => true,
            ],
            'should show indexOf' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_indexOf": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 1, 2],
                    'input2' => 2,
                    'input3' => 2,
                    'output' => 3,
                ],
                'expected' => true,
            ],
            'should show initial' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_initial": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'output' => [1, 2],
                ],
                'expected' => true,
            ],
            'should show intersection' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_intersection": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [2, 1],
                    'input2' => [2, 3],
                    'output' => [2],
                ],
                'expected' => true,
            ],
            'should show join' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_join": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => ['a', 'b', 'c'],
                    'input2' => '~',
                    'output' => 'a~b~c',
                ],
                'expected' => true,
            ],
            'should show last' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_last": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'output' => 3,
                ],
                'expected' => true,
            ],
            'should show lastIndexOf' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_lastIndexOf": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 1, 2],
                    'input2' => 2,
                    'input3' => 2,
                    'output' => 1,
                ],
                'expected' => true,
            ],
            'should show nth' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_nth": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => ['a', 'b', 'c', 'd'],
                    'input2' => -2,
                    'output' => 'c',
                ],
                'expected' => true,
            ],
            'should show slice' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_slice": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3, 4, 5],
                    'input2' => 2,
                    'input3' => 4,
                    'output' => [3, 4],
                ],
                'expected' => true,
            ],
            'should show slice 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_slice": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }

                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3, 4, 5],
                    'input2' => 2,
                    'output' => [3, 4, 5],
                ],
                'expected' => true,
            ],
            'should show sortedIndex' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_sortedIndex": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [30, 50],
                    'input2' => 40,
                    'output' => 1,
                ],
                'expected' => true,
            ],
            'should show sortedIndex 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_sortedIndex": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [30, 50],
                    'input2' => 60,
                    'output' => 2,
                ],
                'expected' => true,
            ],
            'should show sortedIndex 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_sortedIndex": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [4, 5, 5, 5, 6],
                    'input2' => 5,
                    'output' => 1,
                ],
                'expected' => true,
            ],
            'should show sortedIndex 4' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_sortedIndex": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [30, 50],
                    'input2' => 20,
                    'output' => 0,
                ],
                'expected' => true,
            ],
            'should show sortedIndexOf' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_sortedIndexOf": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [4, 5, 5, 5, 6],
                    'input2' => 5,
                    'output' => 1,
                ],
                'expected' => true,
            ],
            'should show sortedLastIndex' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_sortedLastIndex": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [30, 50],
                    'input2' => 40,
                    'output' => 1,
                ],
                'expected' => true,
            ],
            'should show sortedLastIndex 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_sortedLastIndex": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [30, 50],
                    'input2' => 60,
                    'output' => 2,
                ],
                'expected' => true,
            ],
            'should show sortedLastIndex 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_sortedLastIndex": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [4, 5, 5, 5, 6],
                    'input2' => 5,
                    'output' => 4,
                ],
                'expected' => true,
            ],
            'should show sortedLastIndex 4' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_sortedLastIndex": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [30, 50],
                    'input2' => 20,
                    'output' => 0,
                ],
                'expected' => true,
            ],
            'should show sortedLastIndexOf' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_sortedLastIndexOf": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [4, 5, 5, 5, 6],
                    'input2' => 5,
                    'output' => 3,
                ],
                'expected' => true,
            ],
            'should show sortedUniq' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_sortedUniq": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 1, 2],
                    'output' => [1, 2],
                ],
                'expected' => true,
            ],
            'should show tail' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_tail": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'output' => [2, 3],
                ],
                'expected' => true,
            ],
            'should show take' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_take": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'input2' => 2,
                    'output' => [1, 2],
                ],
                'expected' => true,
            ],
            'should show takeRight' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_takeRight": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'input2' => 2,
                    'output' => [2, 3],
                ],
                'expected' => true,
            ],
            'should show takeRightWhile' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_takeRightWhile": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['user' => 'barney', 'age' => 36, 'active' => true], ['user' => 'fred',   'age' => 40, 'active' => false], ['user' => 'pebbles',   'age' => 41, 'active' => false]],
                    'input2' => ['active', false],
                    'output' => [['user' => 'fred',   'age' => 40, 'active' => false], ['user' => 'pebbles',   'age' => 41, 'active' => false]],
                ],
                'expected' => true,
            ],
            'should show takeWhile' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_takeWhile": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['user' => 'barney', 'age' => 36, 'active' => false], ['user' => 'fred',   'age' => 40, 'active' => false], ['user' => 'pebbles',   'age' => 41, 'active' => true]],
                    'input2' => ['active', false],
                    'output' => [['user' => 'barney', 'age' => 36, 'active' => false], ['user' => 'fred',   'age' => 40, 'active' => false]],
                ],
                'expected' => true,
            ],
            'should show union' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_union": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [2],
                    'input2' => [1, 2],
                    'output' => [2, 1],
                ],
                'expected' => true,
            ],
            'should show uniq' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_uniq": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [2, 1, 2],
                    'output' => [2, 1],
                ],
                'expected' => true,
            ],
            'should show unzip' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_unzip": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['a', 1, true], ['b', 2, false]],
                    'output' => [['a', 'b'], [1, 2], [true, false]],
                ],
                'expected' => true,
            ],
            'should show without' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_without": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [2, 1, 2, 3],
                    'input2' => 1,
                    'input3' => 2,
                    'output' => [3],
                ],
                'expected' => true,
            ],
            'should show zip' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_zip": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => ['a', 'b'],
                    'input2' => [1, 2],
                    'input3' => [true, false],
                    'output' => [['a', 1, true], ['b', 2, false]],
                ],
                'expected' => true,
            ],
            'should show zipObject' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_zipObject": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => ['a', 'b'],
                    'input2' => [1, 2],
                    'output' => json_decode('{"a":1,"b":2}'),
                ],
                'expected' => true,
            ],
            'should show zipObjectDeep' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_zipObjectDeep": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => ['a.b[0].c', 'a.b[1].d'],
                    'input2' => [1, 2],
                    'output' => json_decode('{"a":{"b":[{"c":1},{"d":2}]}}'),
                ],
                'expected' => true,
            ],
            // Collection functions
            'should show every' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_every": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['user' => 'barney', 'age' => 36, 'active' => true], ['user' => 'fred',   'age' => 40, 'active' => false], ['user' => 'pebbles',   'age' => 41, 'active' => false]],
                    'input2' => ['active', false],
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show filter' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_filter": [
                        {
                      "var": "data.extra1"
                    },
                        ["active", false]
                      ]
                    },
                    [{
                      "var": "data.extra2"
                    }]
                  ]
                }', true),
                'submissionValues' => ['extra1' => [['user' => 'barney', 'age' => 36, 'active' => true], ['user' => 'fred',   'age' => 40, 'active' => false]],
                    'extra2' => ['user' => 'fred',   'age' => 40, 'active' => false], ],
                'expected' => true,
            ],
            'should show find' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_find": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['user' => 'barney', 'age' => 36, 'active' => true], ['user' => 'fred',   'age' => 40, 'active' => false], ['user' => 'pebbles',   'age' => 41, 'active' => false]],
                    'input2' => ['active', false],
                    'output' => ['user' => 'fred',   'age' => 40, 'active' => false],
                ],
                'expected' => true,
            ],
            'should show findLast' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_findLast": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['user' => 'barney', 'age' => 36, 'active' => true], ['user' => 'fred',   'age' => 40, 'active' => false], ['user' => 'pebbles',   'age' => 41, 'active' => false]],
                    'input2' => ['active', false],
                    'output' => ['user' => 'pebbles',   'age' => 41, 'active' => false],
                ],
                'expected' => true,
            ],
            'should show includes' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_includes": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'input2' => 1,
                    'input3' => 2,
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show includes 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_includes": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'input2' => 1,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show includes 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_includes": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1, "b":2}', true),
                    'input2' => 1,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show includes 4' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_includes": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'abcd',
                    'input2' => 'bc',
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show keyBy' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_keyBy": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['direction' => 'left', 'code' => 97], ['direction' => 'right', 'code' => 100]],
                    'input2' => 'direction',
                    'output' => ['left' => ['direction' => 'left', 'code' => 97], 'right' => ['direction' => 'right', 'code' => 100]],
                ],
                'expected' => true,
            ],
            'should show map' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_map": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['user' => 'barney'], ['user' => 'fred']],
                    'input2' => 'user',
                    'output' => ['barney', 'fred'],
                ],
                'expected' => true,
            ],
            'should show orderBy' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_orderBy": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['user' => 'fred',   'age' => 48], ['user' => 'barney', 'age' => 34], ['user' => 'fred',   'age' => 40], ['user' => 'barney', 'age' => 36]],
                    'input2' => ['user', 'age'],
                    'input3' => ['asc', 'desc'],
                    'output' => [['user' => 'barney', 'age' => 36], ['user' => 'barney', 'age' => 34], ['user' => 'fred',   'age' => 48], ['user' => 'fred',   'age' => 40]],
                ],
                'expected' => true,
            ],
            'should show reject' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_reject": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['user' => 'barney', 'age' => 36, 'active' => true], ['user' => 'fred',   'age' => 40, 'active' => false], ['user' => 'pebbles',   'age' => 41, 'active' => false]],
                    'input2' => ['active', false],
                    'output' => [['user' => 'barney', 'age' => 36, 'active' => true]],
                ],
                'expected' => true,
            ],
            'should show size' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_size": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'output' => 3,
                ],
                'expected' => true,
            ],
            'should show size 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_size": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1, "b":2}', true),
                    'output' => 2,
                ],
                'expected' => true,
            ],
            'should show some' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_some": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['user' => 'barney', 'age' => 36, 'active' => true], ['user' => 'fred',   'age' => 40, 'active' => false], ['user' => 'pebbles',   'age' => 41, 'active' => false]],
                    'input2' => ['active', false],
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show sortBy' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_sortBy": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [['user' => 'fred',   'age' => 48], ['user' => 'barney', 'age' => 34], ['user' => 'fred',   'age' => 40], ['user' => 'barney', 'age' => 36]],
                    'input2' => ['user', 'age'],
                    'output' => [['user' => 'barney', 'age' => 34], ['user' => 'barney', 'age' => 36], ['user' => 'fred',   'age' => 40], ['user' => 'fred',   'age' => 48]],
                ],
                'expected' => true,
            ],
            //Date Function
            'should show now' => [
                'jsonLogic' => json_decode('{
                  "_inRange": [
                    {
                      "_now": [

                      ]
                    },
                    {
                      "var": "data.input1"
                    },
                    {
                      "var": "data.input2"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => (int) (microtime(true) * 1000) - 100000, //give it a buffer for test to run
                    'input2' => (int) (microtime(true) * 1000) + 100000,
                ],
                'expected' => true,
            ],
            // Lang Functions
            'should show castArray' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_castArray": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 1,
                    'output' => [1],
                ],
                'expected' => true,
            ],
            'should show castArray 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_castArray": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1}', true),
                    'output' => json_decode('[{"a":1}]', true),
                ],
                'expected' => true,
            ],
            'should show castArray 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_castArray": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'abc',
                    'output' => ['abc'],
                ],
                'expected' => true,
            ],
            'should show castArray 4' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_castArray": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => null,
                    'output' => [null],
                ],
                'expected' => true,
            ],
            'should show castArray 5' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_castArray": [
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'output' => [],
                ],
                'expected' => true,
            ],
            'should show eq' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_eq": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'a',
                    'input2' => 'a',
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show gt' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_gt": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3,
                    'input2' => 1,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show gt 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_gt": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3,
                    'input2' => 3,
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show gt 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_gt": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 1,
                    'input2' => 3,
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show gte' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_gte": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3,
                    'input2' => 1,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show gte 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_gte": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3,
                    'input2' => 3,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show gte 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_gte": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 1,
                    'input2' => 3,
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isArray' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isArray": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isArray 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isArray": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'abc',
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isArrayLike' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isArrayLike": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isArrayLike 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isArrayLike": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'abc',
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isArrayLike' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isArrayLike": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isArrayLikeObject 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isArrayLikeObject": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'abc',
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isBoolean' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isBoolean": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => false,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isBoolean 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isBoolean": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => true,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isBoolean 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isBoolean": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [],
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isBoolean 4' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isBoolean": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'abc',
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isEmpty' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isEmpty": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => null,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isEmpty 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isEmpty": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => true,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isEmpty 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isEmpty": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 1,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isEmpty 4' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isEmpty": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isEmpty 5' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isEmpty": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1}', true),
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isFinite' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isFinite": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isFinite 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isFinite": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 0.5,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isFinite 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isFinite": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '3',
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isInteger' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isInteger": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isInteger 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isInteger": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 0.5,
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isInteger 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isInteger": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '3',
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isLength' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isLength": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isLength 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isLength": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 0.5,
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isLength 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isLength": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '3',
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isEqual' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                       "var": "data.input1"
                    },
                    {
                      "var": "data.input2"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1 }', true),
                    'input2' => json_decode('{"a":1 }', true),
                ],
                'expected' => true,
            ],
            'should show isError' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isError": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => new \Error(),
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isMatch' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isMatch": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1, "b":2}', true),
                    'input2' => json_decode('{"a":1}', true),
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isMatch 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isMatch": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"b":2}', true),
                    'input2' => json_decode('{"a":1}', true),
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isMatch 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isMatch": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":3, "b":2}', true),
                    'input2' => json_decode('{"a":1}', true),
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isNaN' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isNaN": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => NAN,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isNaN 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isNaN": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'abc',
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isNaN 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isNaN": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [],
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isNull' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isNull": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => null,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isNull 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isNull": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 0,
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isNumber' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isNumber": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isNumber 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isNumber": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '3',
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isObject' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isObject": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{}', true),
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isObject 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isObject": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isObject 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isObject": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => null,
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show isString' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isString": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'abc',
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show isString 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_isString": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 1,
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show lt' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_lt": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3,
                    'input2' => 1,
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show lt 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_lt": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3,
                    'input2' => 3,
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show lt 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_lt": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 1,
                    'input2' => 3,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show lte' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_lte": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3,
                    'input2' => 1,
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show lte 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_lte": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3,
                    'input2' => 3,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show lte 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_lte": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 1,
                    'input2' => 3,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show toArray' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toArray": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1, "b":2}', true),
                    'output' => [1, 2],
                ],
                'expected' => true,
            ],
            'should show toArray 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toArray": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'abc',
                    'output' => ['a', 'b', 'c'],
                ],
                'expected' => true,
            ],
            'should show toArray 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toArray": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 1,
                    'output' => [],
                ],
                'expected' => true,
            ],
            'should show toArray 4' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toArray": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => null,
                    'output' => [],
                ],
                'expected' => true,
            ],
            'should show toFinite' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toFinite": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3.2,
                    'output' => 3.2,
                ],
                'expected' => true,
            ],
            'should show toFinite 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toFinite": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => -14.5,
                    'output' => -14.5,
                ],
                'expected' => true,
            ],
            'should show toFinite 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toFinite": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '3.2',
                    'output' => 3.2,
                ],
                'expected' => true,
            ],
            'should show toInteger' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toInteger": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3.2,
                    'output' => 3,
                ],
                'expected' => true,
            ],
            'should show toInteger 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toInteger": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => -14.5,
                    'output' => -14,
                ],
                'expected' => true,
            ],
            'should show toInteger 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toInteger": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '3.2',
                    'output' => 3,
                ],
                'expected' => true,
            ],
            'should show toLength' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toLength": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3.2,
                    'output' => 3,
                ],
                'expected' => true,
            ],
            'should show toLength 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toLength": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => -14.5,
                    'output' => 0,
                ],
                'expected' => true,
            ],
            'should show toLength 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toLength": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '3.2',
                    'output' => 3,
                ],
                'expected' => true,
            ],
            'should show toNumber' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toNumber": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3.2,
                    'output' => 3.2,
                ],
                'expected' => true,
            ],
            'should show toNumber 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toNumber": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => -14.5,
                    'output' => -14.5,
                ],
                'expected' => true,
            ],
            'should show toNumber 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toNumber": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '3.2',
                    'output' => 3.2,
                ],
                'expected' => true,
            ],
            'should show toSafeInteger' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toSafeInteger": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3.2,
                    'output' => 3,
                ],
                'expected' => true,
            ],
            'should show toSafeInteger 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toSafeInteger": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => -14.5,
                    'output' => -14,
                ],
                'expected' => true,
            ],
            'should show toSafeInteger 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toSafeInteger": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '3.2',
                    'output' => 3,
                ],
                'expected' => true,
            ],
            'should show toString' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toString": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => null,
                    'output' => '',
                ],
                'expected' => true,
            ],
            'should show toString 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toString": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [1, 2, 3],
                    'output' => '1,2,3',
                ],
                'expected' => true,
            ],
            'should show toString 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toString": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3,
                    'output' => '3',
                ],
                'expected' => true,
            ],
            //Math functions
            'should show add' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_add": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 6,
                    'input2' => 4,
                    'output' => 10,
                ],
                'expected' => true,
            ],
            'should show ceil' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_ceil": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 4.006,
                    'output' => 5,
                ],
                'expected' => true,
            ],
            'should show ceil 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_ceil": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 6.004,
                    'input2' => 2,
                    'output' => 6.01,
                ],
                'expected' => true,
            ],
            'should show ceil 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_ceil": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 6040,
                    'input2' => -2,
                    'output' => 6100,
                ],
                'expected' => true,
            ],
            'should show ceil 4' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_ceil": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 2.22,
                    'input2' => 2,
                    'output' => 2.22,
                ],
                'expected' => true,
            ],
            'should show divide' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_divide": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 6,
                    'input2' => 4,
                    'output' => 1.5,
                ],
                'expected' => true,
            ],
            'should show floor' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_floor": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 4.006,
                    'output' => 4,
                ],
                'expected' => true,
            ],
            'should show floor 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_floor": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 0.046,
                    'input2' => 2,
                    'output' => 0.04,
                ],
                'expected' => true,
            ],
            'should show floor 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_floor": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 4060,
                    'input2' => -2,
                    'output' => 4000,
                ],
                'expected' => true,
            ],
            'should show floor 4' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_floor": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 2.22,
                    'input2' => 2,
                    'output' => 2.22,
                ],
                'expected' => true,
            ],
            'should show mean' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_mean": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [4, 2, 8, 6],
                    'output' => 5,
                ],
                'expected' => true,
            ],
            'should show max' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_max": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [4, 2, 8, 6],
                    'output' => 8,
                ],
                'expected' => true,
            ],
            'should show max 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_max": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [],
                    'output' => null,
                ],
                'expected' => true,
            ],
            'should show maxBy' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_maxBy": [
                        {
                      "var": "data.extra1"
                    },
                        "n"
                      ]
                    },
                    {
                      "var": "data.extra2"
                    }
                  ]
                }', true),
                'submissionValues' => ['extra1' => json_decode('[{"n":1},{"n":2},{"n":3},{"h":5}]', true), 'extra2' => json_decode('{"n":3}', true)],
                'expected' => true,
            ],
            'should show min' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_min": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [4, 2, 8, 6],
                    'output' => 2,
                ],
                'expected' => true,
            ],
            'should show min 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_max": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [],
                    'output' => null,
                ],
                'expected' => true,
            ],
            'should show multiply' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_multiply": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 6,
                    'input2' => 4,
                    'output' => 24,
                ],
                'expected' => true,
            ],
            'should show round' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_round": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 4.006,
                    'output' => 4,
                ],
                'expected' => true,
            ],
            'should show round 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_round": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 4.006,
                    'input2' => 2,
                    'output' => 4.01,
                ],
                'expected' => true,
            ],
            'should show round 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_round": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 4060,
                    'input2' => -2,
                    'output' => 4100,
                ],
                'expected' => true,
            ],
            'should show subtract' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_subtract": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 6,
                    'input2' => 4,
                    'output' => 2,
                ],
                'expected' => true,
            ],
            'should show sum' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_sum": [
                        {
                      "var": "data.input1"
                    }

                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [4, 2, 8, 6],
                    'output' => 20,
                ],
                'expected' => true,
            ],
            //Number functions
            'should show clamp' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_clamp": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => -10,
                    'input2' => -5,
                    'input3' => 5,
                    'output' => -5,
                ],
                'expected' => true,
            ],
            'should show clamp 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_clamp": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 10,
                    'input2' => -5,
                    'input3' => 5,
                    'output' => 5,
                ],
                'expected' => true,
            ],
            'should show inRange' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_inRange": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 3,
                    'input2' => 2,
                    'input3' => 4,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show inRange 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_inRange": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 4,
                    'input2' => 8,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show inRange 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_inRange": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 4,
                    'input2' => 2,
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show inRange 4' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_inRange": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 2,
                    'input2' => 2,
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show inRange 5' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_inRange": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 1.2,
                    'input2' => 2,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show inRange 6' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_inRange": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => -3,
                    'input2' => -2,
                    'input3' => -6,
                    'output' => true,
                ],
                'expected' => true,
            ],
            //Object functions
            'should show at' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_at": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{ "a": [{ "b": { "c": 3 } }, 4] }', true),
                    'input2' => ['a[0].b.c', 'a[1]'],
                    'output' => [3, 4],
                ],
                'expected' => true,
            ],
            'should show entries' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_entries": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1, "b":2}', true),
                    'output' => [['a', 1], ['b', 2]],
                ],
                'expected' => true,
            ],
            'should show entriesIn' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_entriesIn": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1, "b":2}', true),
                    'output' => [['a', 1], ['b', 2]],
                ],
                'expected' => true,
            ],
            'should show get' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_get": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{ "a": [{ "b": { "c": 3 } }] }', true),
                    'input2' => 'a[0].b.c',
                    'output' => 3,
                ],
                'expected' => true,
            ],
            'should show get 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_get": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{ "a": [{ "b": { "c": 3 } }] }', true),
                    'input2' => ['a', '0', 'b', 'c'],
                    'output' => 3,
                ],
                'expected' => true,
            ],
            'should show get 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_get": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{ "a": [{ "b": { "c": 3 } }] }', true),
                    'input2' => 'a.b.c',
                    'input3' => 'default',
                    'output' => 'default',
                ],
                'expected' => true,
            ],
            'should show has' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_has": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{ "a": { "b": 2 } }', true),
                    'input2' => 'a',
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show has 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_has": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{ "a": { "b": 2 } }', true),
                    'input2' => 'a.b',
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show has 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_has": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{ "a": { "b": 2 } }', true),
                    'input2' => ['a', 'b'],
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show hasIn' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_hasIn": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{ "a": { "b": 2 } }', true),
                    'input2' => 'a',
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show hasIn 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_hasIn": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{ "a": { "b": 2 } }', true),
                    'input2' => 'a.b',
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show hasIn 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_hasIn": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{ "a": { "b": 2 } }', true),
                    'input2' => ['a', 'b'],
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show invert' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_invert": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1, "b":2, "c": 1}', true),
                    'output' => json_decode('{"1": "c", "2": "b"}', true),
                ],
                'expected' => true,
            ],
            'should show keys' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_keys": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1, "b":2}', true),
                    'output' => ['a', 'b'],
                ],
                'expected' => true,
            ],
            'should show keys 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_keys": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'hi',
                    'output' => ['0', '1'],
                ],
                'expected' => true,
            ],
            'should show keysIn' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_keysIn": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1, "b":2}', true),
                    'output' => ['a', 'b'],
                ],
                'expected' => true,
            ],
            'should show keysIn 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_keysIn": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'hi',
                    'output' => ['0', '1'],
                ],
                'expected' => true,
            ],
            'should show omit' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_omit": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{ "a": 1, "b": "2", "c": 3 }', true),
                    'input2' => ['a', 'c'],
                    'output' => json_decode('{ "b": "2" }', true),
                ],
                'expected' => true,
            ],
            'should show pick' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_pick": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{ "a": 1, "b": "2", "c": 3 }', true),
                    'input2' => ['a', 'c'],
                    'output' => json_decode('{ "a": 1, "c": 3 }', true),
                ],
                'expected' => true,
            ],
            'should show result' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_result": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{ "a": [{ "b": { "c": 3 } }] }', true),
                    'input2' => 'a[0].b.c',
                    'output' => 3,
                ],
                'expected' => true,
            ],
            'should show result 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_result": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{ "a": [{ "b": { "c": 3 } }] }', true),
                    'input2' => ['a', '0', 'b', 'c'],
                    'output' => 3,
                ],
                'expected' => true,
            ],
            'should show result 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_result": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{ "a": [{ "b": { "c": 3 } }] }', true),
                    'input2' => 'a.b.c',
                    'input3' => 'default',
                    'output' => 'default',
                ],
                'expected' => true,
            ],
            'should show toPairs' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toPairs": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1, "b":2}', true),
                    'output' => [['a', 1], ['b', 2]],
                ],
                'expected' => true,
            ],
            'should show toPairsIn' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toPairsIn": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1, "b":2}', true),
                    'output' => [['a', 1], ['b', 2]],
                ],
                'expected' => true,
            ],
            'should show values' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_values": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1, "b":2}', true),
                    'output' => [1, 2],
                ],
                'expected' => true,
            ],
            'should show values 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_values": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'hi',
                    'output' => ['h', 'i'],
                ],
                'expected' => true,
            ],
            'should show valuesIn' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_valuesIn": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('{"a":1, "b":2}', true),
                    'output' => [1, 2],
                ],
                'expected' => true,
            ],
            'should show valuesIn 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_valuesIn": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'hi',
                    'output' => ['h', 'i'],
                ],
                'expected' => true,
            ],
            //String functions
            'should show camelCase' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_camelCase": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'Foo Bar',
                    'output' => 'fooBar',
                ],
                'expected' => true,
            ],
            'should show capitalize' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_capitalize": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'FRED',
                    'output' => 'Fred',
                ],
                'expected' => true,
            ],
            'should show deburr' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_deburr": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'déjà vu',
                    'output' => 'deja vu',
                ],
                'expected' => true,
            ],
            'should show endsWith' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_endsWith": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'abc',
                    'input2' => 'b',
                    'input3' => 2,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show escape' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_escape": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'fred, barney, & pebbles',
                    'output' => 'fred, barney, &amp; pebbles',
                ],
                'expected' => true,
            ],
            'should show escapeRegExp' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_escapeRegExp": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '[lodash](https://lodash.com/)',
                    'output' => '\[lodash\]\(https://lodash\.com/\)',
                ],
                'expected' => true,
            ],
            'should show kebabCase' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_kebabCase": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '__FOO_BAR__',
                    'output' => 'foo-bar',
                ],
                'expected' => true,
            ],
            'should show lowerCase' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_lowerCase": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '__FOO_BAR__',
                    'output' => 'foo bar',
                ],
                'expected' => true,
            ],
            'should show lowerFirst' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_lowerFirst": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'FRED',
                    'output' => 'fRED',
                ],
                'expected' => true,
            ],
            'should show pad' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_pad": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'abc',
                    'input2' => 8,
                    'input3' => '_-',
                    'output' => '_-abc_-_',
                ],
                'expected' => true,
            ],
            'should show padEnd' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_padEnd": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'abc',
                    'input2' => 6,
                    'input3' => '_-',
                    'output' => 'abc_-_',
                ],
                'expected' => true,
            ],
            'should show padStart' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_padStart": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'abc',
                    'input2' => 6,
                    'input3' => '_-',
                    'output' => '_-_abc',
                ],
                'expected' => true,
            ],
            'should show parseInt' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_parseInt": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '08',
                    'output' => 8,
                ],
                'expected' => true,
            ],
            'should show repeat' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_repeat": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '*',
                    'input2' => 5,
                    'output' => '*****',
                ],
                'expected' => true,
            ],
            'should show replace' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_replace": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'Hi Fred',
                    'input2' => 'Fred',
                    'input3' => 'Barney',
                    'output' => 'Hi Barney',
                ],
                'expected' => true,
            ],
            'should show snakeCase' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_snakeCase": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'Foo Bar',
                    'output' => 'foo_bar',
                ],
                'expected' => true,
            ],
            'should show split' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_split": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'a-b-c',
                    'input2' => '-',
                    'input3' => 2,
                    'output' => ['a', 'b'],
                ],
                'expected' => true,
            ],
            'should show startCase' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_startCase": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '--foo-bar--',
                    'output' => 'Foo Bar',
                ],
                'expected' => true,
            ],
            'should show startsWith' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_startsWith": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'abc',
                    'input2' => 'b',
                    'input3' => 1,
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show toLower' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toLower": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '__FOO_BAR__',
                    'output' => '__foo_bar__',
                ],
                'expected' => true,
            ],
            'should show toUpper' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toUpper": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '__foo_bar__',
                    'output' => '__FOO_BAR__',
                ],
                'expected' => true,
            ],
            'should show trim' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_trim": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '-_-abc-_-',
                    'input2' => '_-',
                    'output' => 'abc',
                ],
                'expected' => true,
            ],
            'should show trimEnd' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_trimEnd": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '-_-abc-_-',
                    'input2' => '_-',
                    'output' => '-_-abc',
                ],
                'expected' => true,
            ],
            'should show trimStart' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_trimStart": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '-_-abc-_-',
                    'input2' => '_-',
                    'output' => 'abc-_-',
                ],
                'expected' => true,
            ],
            'should show truncate' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_truncate": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'hi-diddly-ho there, neighborino',
                    'input2' => ['length' => 24, 'separator' => ' '],
                    'output' => 'hi-diddly-ho there,...',
                ],
                'expected' => true,
            ],
            'should show unescape' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_unescape": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'fred, barney, &amp; pebbles',
                    'output' => 'fred, barney, & pebbles',
                ],
                'expected' => true,
            ],
            'should show upperCase' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_upperCase": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => '__foo_bar__',
                    'output' => 'FOO BAR',
                ],
                'expected' => true,
            ],
            'should show upperFirst' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_upperFirst": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'fred',
                    'output' => 'Fred',
                ],
                'expected' => true,
            ],
            'should show words' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_words": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'fred, barney, & pebbles',
                    'output' => ['fred', 'barney', 'pebbles'],
                ],
                'expected' => true,
            ],
            // Util functions
            'should show constant' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_times": [
                        {
                      "var": "data.input1"
                    },
                        {
                           "_constant": [
                        {
                      "var": "data.input2"
                    }
                      ]
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 5,
                    'input2' => 'abc',
                    'output' => ['abc', 'abc', 'abc', 'abc', 'abc'],
                ],
                'expected' => true,
            ],
            'should show defaultTo' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_defaultTo": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 1,
                    'input2' => 10,
                    'output' => 1,
                ],
                'expected' => true,
            ],
            'should show defaultTo 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_defaultTo": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => null,
                    'input2' => 10,
                    'output' => 10,
                ],
                'expected' => true,
            ],
            'should show defaultTo 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_defaultTo": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => NAN,
                    'input2' => 10,
                    'output' => 10,
                ],
                'expected' => true,
            ],
            'should show identity' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_identity": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('[{"n":1},{"n":2},{"n":3},{"h":5}]', true),
                    'output' => json_decode('[{"n":1},{"n":2},{"n":3},{"h":5}]', true),
                ],
                'expected' => true,
            ],
            'should show iteratee' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_filter": [
                        {
                      "var": "data.extra1"
                    },
                        {
                      "_iteratee": [
                        {
                      "var": "data.extra2"
                    }
                      ]
                    }
                      ]
                    },
                    [{
                      "var": "data.output"
                    }]
                  ]
                }', true),
                'submissionValues' => [
                    'extra1' => [['user' => 'barney', 'age' => 36, 'active' => true], ['user' => 'fred',   'age' => 40, 'active' => false]],
                    'extra2' => ['user', 'fred'],
                    'output' => ['user' => 'fred',   'age' => 40, 'active' => false], ],
                'expected' => true,
            ],
            'should show matches' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_filter": [
                        {
                      "var": "data.input1"
                    },{
                     "_matches": [   {
                      "var": "data.input2"
                    }]}
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('[{ "a": 1, "b": 2, "c": 3 },{ "a": 4, "b": 5, "c": 6 }]', true),
                    'input2' => json_decode('{ "a": 4, "c": 6 }', true),
                    'output' => json_decode('[{ "a": 4, "b": 5, "c": 6 }]', true),
                ],
                'expected' => true,
            ],
            'should show matchesProperty' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_filter": [
                        {
                      "var": "data.input1"
                    },{
                     "_matchesProperty": [   {
                      "var": "data.input2"
                    },{
                      "var": "data.input3"
                    }]}
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('[{ "a": 1, "b": 2, "c": 3 },{ "a": 4, "b": 5, "c": 6 }]', true),
                    'input2' => 'a',
                    'input3' => 4,
                    'output' => json_decode('[{ "a": 4, "b": 5, "c": 6 }]', true),
                ],
                'expected' => true,
            ],
            'should show property' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_map": [
                        {
                      "var": "data.input1"
                        },
                        {
                      "_property": [
                        {
                      "var": "data.input2"
                        }
                      ]
                        }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('[{ "a": { "b": 2 } },{ "a": { "b": 1 } }]', true),
                    'input2' => 'a.b',
                    'output' => [2, 1],
                ],
                'expected' => true,
            ],
            'should show range' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_range": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 4,
                    'output' => [0, 1, 2, 3],
                ],
                'expected' => true,
            ],
            'should show range 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_range": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => -4,
                    'output' => [0, -1, -2, -3],
                ],
                'expected' => true,
            ],
            'should show range 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_range": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 0,
                    'output' => [],
                ],
                'expected' => true,
            ],
            'should show range 4' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_range": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 1,
                    'input2' => 5,
                    'output' => [1, 2, 3, 4],
                ],
                'expected' => true,
            ],
            'should show range 5' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_range": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 0,
                    'input2' => 20,
                    'input3' => 5,
                    'output' => [0, 5, 10, 15],
                ],
                'expected' => true,
            ],
            'should show range 6' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_range": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 1,
                    'input2' => 4,
                    'input3' => 0,
                    'output' => [1, 1, 1],
                ],
                'expected' => true,
            ],
            'should show range 7' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_range": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 0,
                    'input2' => 21,
                    'input3' => 5,
                    'output' => [0, 5, 10, 15, 20],
                ],
                'expected' => true,
            ],
            'should show rangeRight' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_rangeRight": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 4,
                    'output' => [3, 2, 1, 0],
                ],
                'expected' => true,
            ],
            'should show rangeRight 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_rangeRight": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => -4,
                    'output' => [-3, -2, -1, 0],
                ],
                'expected' => true,
            ],
            'should show rangeRight 3' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_rangeRight": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 0,
                    'output' => [],
                ],
                'expected' => true,
            ],
            'should show rangeRight 4' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_rangeRight": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 1,
                    'input2' => 5,
                    'output' => [4, 3, 2, 1],
                ],
                'expected' => true,
            ],
            'should show rangeRight 5' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_rangeRight": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 0,
                    'input2' => 20,
                    'input3' => 5,
                    'output' => [15, 10, 5, 0],
                ],
                'expected' => true,
            ],
            'should show rangeRight 6' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_rangeRight": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 1,
                    'input2' => 4,
                    'input3' => 0,
                    'output' => [1, 1, 1],
                ],
                'expected' => true,
            ],
            'should show rangeRight 7' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_rangeRight": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 0,
                    'input2' => 21,
                    'input3' => 5,
                    'output' => [20, 15, 10, 5, 0],
                ],
                'expected' => true,
            ],
            'should show stubArray' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_stubArray": [
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'output' => [],
                ],
                'expected' => true,
            ],
            'should show stubFalse' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_stubFalse": [
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'output' => false,
                ],
                'expected' => true,
            ],
            'should show stubObject' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_stubObject": [
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'output' => json_decode('{}', true),
                ],
                'expected' => true,
            ],
            'should show stubString' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_stubString": [
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'output' => '',
                ],
                'expected' => true,
            ],
            'should show stubTrue' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_stubTrue": [
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'output' => true,
                ],
                'expected' => true,
            ],
            'should show toPath' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toPath": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'a[0].b.c',
                    'output' => ['a', '0', 'b', 'c'],
                ],
                'expected' => true,
            ],
            'should show toPath 2' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_toPath": [
                        {
                      "var": "data.input1"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 'a.b.c',
                    'output' => ['a', 'b', 'c'],
                ],
                'expected' => true,
            ],
            'should show times' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_times": [
                        {
                      "var": "data.input1"
                    },
                        {
                           "_constant": [
                        {
                      "var": "data.input2"
                    }
                      ]
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => 4,
                    'input2' => 0,
                    'output' => [0, 0, 0, 0],
                ],
                'expected' => true,
            ],

            /*

            Notes
            sample, and sampleSize, shuffle, random seems working but unable to test due to random
            unable to test constant


            flatMap, flatMapDeep, flatMapDepth, groupBY, invokeMap, partition, countBy, zipWith, unzipWith, uniqBy,
            uniqWtih, unionBy, intersectionBy, differenceBy, dropRightWhile, dropWhile, differenceWith, differenceBy,
            pickBy, mapKeys, mapValues have issues due to taking a callable function which does not work between
            Javascript and PHP

            isFunction cannot work because function name issue between JS and PHP similar to callable above

            'should show dropRightWhile' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_dropRightWhile": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [[ 'user' => 'barney', 'age' => 36, 'active' => true], [ 'user' => 'fred',   'age' => 40, 'active' => false], [ 'user' => 'pebbles',   'age' => 41, 'active' => false]],
                    'input2' => ['active', false],
                    'output' => [[ 'user' => 'barney', 'age' => 36, 'active' => true]]
                ],
                'expected' => true,
            ]

            'should show differenceWith' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_differenceWith": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => json_decode('[{ "x": 1, "y": 2 }, { "x": 2, "y": 1 }]', true),
                    'input2' => json_decode('[{ "x": 1, "y": 2 }]', true),
                    'input3' => '_.isEqual', //does not work have to pass in callable
                    'output' => json_decode('[{ "x": 2, "y": 1 }]', true)
                ],
                'expected' => true,
            ]
            'should show differenceBy' => [
                'jsonLogic' => json_decode('{
                  "_isEqual": [
                    {
                      "_differenceBy": [
                        {
                      "var": "data.input1"
                    },
                        {
                      "var": "data.input2"
                    },
                        {
                      "var": "data.input3"
                    }
                      ]
                    },
                    {
                      "var": "data.output"
                    }
                  ]
                }', true),
                'submissionValues' => [
                    'input1' => [2.1, 1.2],
                    'input2' => [2.3, 3.4],
                    'input3' => 'floor', // this needed to be MAth.floor, but only works with floor
                    'output' => [1.2],
                ],
                'expected' => true,
            ]*/

        ];
    }
}
