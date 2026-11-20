<?php

namespace Northwestern\SysDev\DynamicForms\Tests;

use InvalidArgumentException;
use Northwestern\SysDev\DynamicForms\DefinitionCheckRegistry;
use Northwestern\SysDev\DynamicForms\Forms\Definition\Checks\JsonValidationCheck;
use Northwestern\SysDev\DynamicForms\Tests\Fixtures\Definition\DuplicateLabelCheck;
use Northwestern\SysDev\DynamicForms\Tests\Fixtures\Definition\FlaggedKeyCheck;
use Northwestern\SysDev\DynamicForms\Tests\Fixtures\Definition\FlaggedKeyPrefix;
use Orchestra\Testbench\TestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(DefinitionCheckRegistry::class)]
final class DefinitionCheckRegistryTest extends TestCase
{
    public function testDefaults(): void
    {
        $registry = new DefinitionCheckRegistry;

        $this->assertCount(1, $registry->componentChecks());
        $this->assertInstanceOf(JsonValidationCheck::class, $registry->componentChecks()[0]);
        $this->assertSame([], $registry->formChecks());
    }

    public function testRegisterInstances(): void
    {
        $componentCheck = new FlaggedKeyCheck(new FlaggedKeyPrefix);
        $formCheck = new DuplicateLabelCheck;

        $registry = new DefinitionCheckRegistry;
        $registry->register($componentCheck);
        $registry->register($formCheck);

        $this->assertSame($componentCheck, $registry->componentChecks()[1]);
        $this->assertSame([$formCheck], $registry->formChecks());
    }

    public function testRegisterClassNameResolvesFromContainer(): void
    {
        $this->app->instance(FlaggedKeyPrefix::class, new FlaggedKeyPrefix('injected'));

        $registry = new DefinitionCheckRegistry;
        $registry->register(FlaggedKeyCheck::class);
        $registry->register(DuplicateLabelCheck::class);

        $this->assertInstanceOf(FlaggedKeyCheck::class, $registry->componentChecks()[1]);
        $this->assertInstanceOf(DuplicateLabelCheck::class, $registry->formChecks()[0]);
    }

    public function testRegisterClassNameIsResolvedLazily(): void
    {
        $registry = new DefinitionCheckRegistry;
        $registry->register(FlaggedKeyCheck::class);

        // Bound after registration, like an app registering checks before its dependencies are bound
        $prefix = new FlaggedKeyPrefix('late');
        $this->app->instance(FlaggedKeyPrefix::class, $prefix);

        $check = $registry->componentChecks()[1];
        $this->assertSame($prefix, (fn () => $this->prefix)->call($check));
    }

    public function testRegisterRejectsNonCheckClass(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new DefinitionCheckRegistry)->register(\stdClass::class);
    }
}
