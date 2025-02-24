<?php

namespace Northwestern\SysDev\DynamicForms\Tests;

use Northwestern\SysDev\DynamicForms\RuleBag;
use Orchestra\Testbench\TestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\Northwestern\SysDev\DynamicForms\RuleBag::class)]
final class RuleBagTest extends TestCase
{
    public function testAdd(): void
    {
        $bag = $this->getBag();

        $bag->add('required');
        $this->assertEquals(1, count($bag->rules()['Test']));
    }

    public function testAddIfNotNull(): void
    {
        $bag = $this->getBag();

        $bag->addIfNotNull('required', null);
        $bag->addIfNotNull('string', 'zzz');
        $this->assertEquals(1, count($bag->rules()['Test']));
    }

    public function testAddIf(): void
    {
        $bag = $this->getBag();

        $bag->addIf('required', false);
        $bag->addIfNotNull('string', true);
        $this->assertEquals(1, count($bag->rules()['Test']));
    }

    public function testRules(): void
    {
        $bag = $this->getBag();
        $this->assertEquals(['Test' => []], $bag->rules());
    }

    protected function getBag(string $key = 'Test', array $bag = []): RuleBag
    {
        return new RuleBag($key, $bag);
    }
}
