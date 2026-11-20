<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Laravel;

use Illuminate\Http\Request;
use Northwestern\SysDev\DynamicForms\DynamicFormsProvider;
use Orchestra\Testbench\TestCase;

/**
 * Ensures the provider registers the request macros. The macro implementations are tested separately.
 */
final class RequestMacrosTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [DynamicFormsProvider::class];
    }

    public function testValidateDynamicForm(): void
    {
        $definition = json_encode(['components' => [['key' => 'name', 'type' => 'textfield']]]);

        $values = Request::create('/')->validateDynamicForm($definition, json_encode(['name' => 'Nick']));

        $this->assertSame('Nick', $values['name']);
    }
}
