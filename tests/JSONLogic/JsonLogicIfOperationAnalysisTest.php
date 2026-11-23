<?php

namespace Northwestern\SysDev\DynamicForms\Tests\JSONLogic;

use Northwestern\SysDev\DynamicForms\JSONLogic\JsonLogicIfOperationAnalysis;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(JsonLogicIfOperationAnalysis::class)]
final class JsonLogicIfOperationAnalysisTest extends TestCase
{
    #[DataProvider('mayReturnTrueProvider')]
    public function testMayReturnTrue(mixed $expression, bool $expected): void
    {
        $this->assertSame($expected, JsonLogicIfOperationAnalysis::mayReturnTrue($expression));
    }

    public static function mayReturnTrueProvider(): array
    {
        $condition = ['==' => [['var' => 'a'], 'b']];

        return [
            // Literals
            'true' => [true, true],
            'false' => [false, false],
            'null' => [null, false],
            'string' => ['true', false],
            'number' => [1, false],
            'empty array' => [[], false],
            'array containing true' => [[true], false],
            'multi-key object containing true' => [['a' => true, 'b' => true], false],

            // if
            'if with true output' => [['if' => [$condition, true, 'Error']], true],
            'if with true else' => [['if' => [$condition, 'Error', true]], true],
            'if with only messages' => [['if' => [$condition, 'Error', 'Other error']], false],
            'if with true only in condition' => [['if' => [true, 'Error', 'Other error']], false],
            'if with no arguments' => [['if' => []], false],
            'if with only an else' => [['if' => [true]], true],
            'chained if with true in middle output' => [['if' => [$condition, 'Error', $condition, true, 'Error']], true],
            'chained if with true else' => [['if' => [$condition, 'Error', $condition, 'Error', true]], true],
            'chained if with true only in conditions' => [['if' => [true, 'Error', true, 'Error', 'Error']], false],
            'chained if without else' => [['if' => [$condition, 'Error', $condition, 'Error']], false],
            'ternary alias' => [['?:' => [$condition, true, 'Error']], true],

            // Pass-through operations
            'or of messages' => [['or' => ['Error', 'Other error']], false],
            'or with true' => [['or' => ['Error', true]], true],
            'or with var' => [['or' => ['Error', ['var' => 'a']]], true],
            'and of messages' => [['and' => ['Error', 'Other error']], false],
            'and with comparison' => [['and' => [$condition, $condition]], true],
            'log of true' => [['log' => true], true],
            'log of message' => [['log' => 'Error'], false],

            // Operations that never return true
            'cat' => [['cat' => [true]], false],
            'substr' => [['substr' => ['true', 0, 4]], false],
            'addition' => [['+' => [1, 1]], false],
            'merge' => [['merge' => [[true], [true]]], false],
            'map' => [['map' => [['var' => 'list'], true]], false],
            'filter' => [['filter' => [['var' => 'list'], true]], false],
            'missing' => [['missing' => ['a']], false],

            // Operations that may return true
            'comparison' => [$condition, true],
            'not' => [['!' => ['Error']], true],
            'in' => [['in' => ['a', ['a']]], true],
            'some' => [['some' => [['var' => 'list'], true]], true],
            'var' => [['var' => 'a'], true],
            'reduce' => [['reduce' => [['var' => 'list'], true, false]], true],
            'unknown operation' => [['_isEqual' => [1, 1]], true],
        ];
    }

    public function testDeeplyNestedTrue(): void
    {
        $this->assertTrue(JsonLogicIfOperationAnalysis::mayReturnTrue($this->nest(true, 50)));
    }

    public function testDeeplyNestedMessages(): void
    {
        $this->assertFalse(JsonLogicIfOperationAnalysis::mayReturnTrue($this->nest('Error', 50)));
    }

    /**
     * Nest the innermost expression in the "then" output of $depth "if"s, with messages everywhere else.
     */
    private function nest(mixed $innermost, int $depth): array
    {
        $expression = $innermost;

        for ($i = 0; $i < $depth; $i++) {
            $expression = ['if' => [['var' => "level{$i}"], $expression, "Error at level {$i}"]];
        }

        return $expression;
    }
}
