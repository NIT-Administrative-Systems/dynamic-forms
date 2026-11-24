<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Laravel;

use Illuminate\Validation\ValidationException;
use Northwestern\SysDev\DynamicForms\Laravel\ValidateDynamicForm;
use Northwestern\SysDev\DynamicForms\ResourceRegistry;
use Orchestra\Testbench\TestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(ValidateDynamicForm::class)]
final class ValidateDynamicFormTest extends TestCase
{
    public function testReturnsValuesWhenValid(): void
    {
        $values = ValidateDynamicForm::macro()($this->definition(), json_encode(['name' => 'Nick']));

        $this->assertSame('Nick', $values['name']);
    }

    public function testAcceptsResourceRegistry(): void
    {
        $values = ValidateDynamicForm::macro()($this->definition(), json_encode(['name' => 'Nick']), new ResourceRegistry);

        $this->assertSame('Nick', $values['name']);
    }

    public function testThrowsWhenInvalid(): void
    {
        $this->app['request']->headers->set('referer', 'http://localhost/previous');

        try {
            ValidateDynamicForm::macro()($this->definition(), json_encode(['name' => '']));
            $this->fail('Expected a ValidationException.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('name', $e->errors());
            $this->assertSame('http://localhost/previous', $e->redirectTo);
        }
    }

    private function definition(): string
    {
        return json_encode([
            'components' => [[
                'key' => 'name',
                'label' => 'Name',
                'type' => 'textfield',
                'validate' => ['required' => true],
            ]],
        ], JSON_THROW_ON_ERROR);
    }
}
