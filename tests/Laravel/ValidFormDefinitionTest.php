<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Laravel;

use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Validator;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;
use Northwestern\SysDev\DynamicForms\Errors\UnknownComponentError;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssueType;
use Northwestern\SysDev\DynamicForms\ResourceRegistry;
use Northwestern\SysDev\DynamicForms\Laravel\ValidFormDefinition;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

#[\PHPUnit\Framework\Attributes\CoversClass(ValidFormDefinition::class)]
final class ValidFormDefinitionTest extends TestCase
{
    private const INVALID_STRUCTURE = ['if' => [true, 'Invalid']];
    private const MISSING_TRUE = ['if' => [true, 'Invalid', false]];
    private const VALID = ['if' => [true, true, 'Invalid']];

    #[DataProvider('issuesProvider')]
    public function testIssues(array $rules, bool $failOnWarnings, int $expectedMessages): void
    {
        $messages = $this->messages($this->definition($rules), new ValidFormDefinition(failOnWarnings: $failOnWarnings));

        $this->assertCount($expectedMessages, $messages);
    }

    public static function issuesProvider(): array
    {
        return [
            'clean' => [['a' => self::VALID], true, 0],
            'clean without failOnWarnings' => [['a' => self::VALID], false, 0],
            'no components with rules' => [[], true, 0],
            'warning fails by default' => [['a' => self::MISSING_TRUE], true, 1],
            'warning passes without failOnWarnings' => [['a' => self::MISSING_TRUE], false, 0],
            'error fails by default' => [['a' => self::INVALID_STRUCTURE], true, 1],
            'error fails without failOnWarnings' => [['a' => self::INVALID_STRUCTURE], false, 1],
            'one message per issue' => [['a' => self::INVALID_STRUCTURE, 'b' => self::MISSING_TRUE], true, 2],
            'only errors without failOnWarnings' => [['a' => self::INVALID_STRUCTURE, 'b' => self::MISSING_TRUE], false, 1],
        ];
    }

    public function testMessageUsesLabelAndKey(): void
    {
        $definition = json_encode(['components' => [[
            'key' => 'class',
            'label' => 'Class Year',
            'type' => 'textfield',
            'validate' => ['json' => self::MISSING_TRUE],
        ]]]);

        $this->assertSame(
            ['Class Year (class): '.DefinitionIssueType::JsonValidationMissingTrue->description()],
            $this->messages($definition, new ValidFormDefinition),
        );
    }

    public function testMessageFallsBackToKey(): void
    {
        $this->assertSame(
            ['a: '.DefinitionIssueType::JsonValidationMissingTrue->description()],
            $this->messages($this->definition(['a' => self::MISSING_TRUE]), new ValidFormDefinition),
        );
    }

    public function testMessageFallsBackToKeyWhenComponentLookupMisses(): void
    {
        // Nested numeric keys are renumbered when the component tree is flattened, so the lookup by key misses.
        $definition = json_encode(['components' => [[
            'key' => 'panel',
            'type' => 'panel',
            'components' => [[
                'key' => '5',
                'label' => 'Five',
                'type' => 'textfield',
                'validate' => ['json' => self::MISSING_TRUE],
            ]],
        ]]]);

        $this->assertSame(
            ['5: '.DefinitionIssueType::JsonValidationMissingTrue->description()],
            $this->messages($definition, new ValidFormDefinition),
        );
    }

    public function testNonStringFailsWithoutReporting(): void
    {
        Exceptions::fake();

        $this->assertSame(
            ['The definition must be a form definition JSON string.'],
            $this->messages(['components' => []], new ValidFormDefinition),
        );
        Exceptions::assertNothingReported();
    }

    /**
     * @param  class-string<\Throwable>  $expectedException
     */
    #[DataProvider('unloadableProvider')]
    public function testUnloadableDefinitionsFailAndReport(string $value, string $expectedException, string $expectedMessage): void
    {
        Exceptions::fake();

        $this->assertSame(
            ['The definition could not be loaded.'],
            $this->messages($value, new ValidFormDefinition),
        );
        Exceptions::assertReported($expectedException);
        $this->assertStringContainsString($expectedMessage, Exceptions::reported()[0]->getMessage());
    }

    public static function unloadableProvider(): array
    {
        return [
            'malformed JSON' => ['{', InvalidDefinitionError::class, 'Expected path missing'],
            'missing components' => ['{}', InvalidDefinitionError::class, 'Expected path missing'],
            'component missing key' => [
                json_encode(['components' => [['type' => 'textfield']]]),
                InvalidDefinitionError::class,
                'required component props',
            ],
            'unknown component type' => [
                json_encode(['components' => [['key' => 'a', 'type' => 'nope']]]),
                UnknownComponentError::class,
                'Component "nope" is unknown',
            ],
        ];
    }

    public function testAcceptsResourceRegistry(): void
    {
        $rule = new ValidFormDefinition(resourceRegistry: new ResourceRegistry);

        $this->assertSame([], $this->messages($this->definition(['a' => self::VALID]), $rule));
    }

    /**
     * @return list<string>
     */
    private function messages(mixed $value, ValidFormDefinition $rule): array
    {
        return Validator::make(['definition' => $value], ['definition' => [$rule]])
            ->errors()
            ->get('definition');
    }

    /**
     * @param  array<string, array>  $rules  JSON Logic validation rules, keyed by component key
     */
    private function definition(array $rules): string
    {
        $components = [];
        foreach ($rules as $key => $rule) {
            $components[] = ['key' => $key, 'type' => 'textfield', 'validate' => ['json' => $rule]];
        }

        return json_encode(['components' => $components], JSON_THROW_ON_ERROR);
    }
}
