<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Select;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;
use Northwestern\SysDev\DynamicForms\ResourceRegistry;
use Northwestern\SysDev\DynamicForms\Resources\ResourceInterface;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Select
 */
class SelectTest extends InputComponentTestCase
{
    protected string $componentClass = Select::class;
    protected array $defaultAdditional = [
        'dataSrc' => 'values',
        'data' => [
            'values' => [
                ['label' => 'Foo', 'value' => 'foo'],
                ['label' => 'Bar', 'value' => 'bar'],
                ['label' => 'Number', 'value' => '1', 'shortcut' => ''],
                ['label' => 'Notrim ', 'value' => 'Notrim ', 'shortcut' => ''],
            ],
        ],
    ];

    /**
     * @covers ::validate
     */
    public function testWithNoValuesProvided(): void
    {
        $component = $this->getComponent(additional: ['data' => null]);

        $bag = $component->validate();
        $this->assertEquals(true, $bag->isEmpty());
    }

    /**
     * @covers ::processValidations
     * @covers ::validate
     */
    public function testValidationInMultipleModeWithNull(): void
    {
        $component = $this->getComponent(
            hasMultipleValues: true,
            submissionValue: null,
        );

        $bag = $component->validate();
        $this->assertEquals(true, $bag->isEmpty());
    }

    public function validationsProvider(): array
    {
        return [
            'not required passes' => [[], '', true],
            'required passes' => [['required' => true], 'foo', true],
            'required fails' => [['required' => true], '', false],
            'invalid values always rejected' => [[], 'not a valid value', false],
            'passes with integer' => [['required' => true], 1, true],
            'passes with trim' => [['required' => true], 'Notrim', true], // Laravel middleware would trim submitted value
        ];
    }

    public function submissionValueProvider(): array
    {
        return [
            'no transformations' => [null, 'foo', 'foo'],
            'integer' => [null, 1, '1'],
            'upper' => [CaseEnum::UPPER, 'foo', 'foo'],
            'lower' => [CaseEnum::LOWER, 'foo', 'foo'],
        ];
    }

    /**
     * @covers ::dataSource
     */
    public function testDataSource(): void
    {
        $this->assertEquals(Select::DATA_SRC_VALUES, $this->getComponent()->dataSource());
    }

    /**
     * @covers ::optionValues
     */
    public function testOptionValues(): void
    {
        $this->assertEquals(['foo', 'bar', 1, 'Notrim'], $this->getComponent()->optionValues());
    }

    /**
     * @covers ::options
     */
    public function testOptions(): void
    {
        $expected = [
            'foo' => 'Foo',
            'bar' => 'Bar',
            '1' => 'Number',
            'Notrim' => 'Notrim',
        ];

        $this->assertEquals($expected, $this->getComponent()->options());
    }

    /**
     * @covers ::initSrcOther
     * This will need to be updated every time support for a new data source is added
     */
    public function testInitSrcOther(): void
    {
        $this->defaultAdditional['dataSrc'] = Select::DATA_SRC_URL;
        $this->expectException(InvalidDefinitionError::class);
        $this->getComponent();

        $this->defaultAdditional['dataSrc'] = Select::DATA_SRC_CUSTOM;
        $this->expectException(InvalidDefinitionError::class);
        $this->getComponent();

        $this->defaultAdditional['dataSrc'] = Select::DATA_SRC_INDEXED_DB;
        $this->expectException(InvalidDefinitionError::class);
        $this->getComponent();

        $this->defaultAdditional['dataSrc'] = Select::DATA_SRC_JSON;
        $this->expectException(InvalidDefinitionError::class);
        $this->getComponent();

//        We DON'T want these to throw an error
        $this->defaultAdditional['dataSrc'] = Select::DATA_SRC_RESOURCE;
        $resourceComponent = $this->getComponent();
        $this->assertEquals(Select::DATA_SRC_RESOURCE, $resourceComponent->dataSource());

        $this->defaultAdditional['dataSrc'] = Select::DATA_SRC_VALUES;
        $valuesComponent = $this->getComponent();
        $this->assertEquals(Select::DATA_SRC_RESOURCE, $valuesComponent->dataSource());
    }

    /**
     * @covers ::setResourceRegistry
     * @covers ::initSrcValues
     */
    public function testSetResourceRegistry_valuesDataSrc(): void
    {
        $valuesComponent = $this->getComponent();
        $resourceRegistry = $this->app->make(ResourceRegistry::class);
        $valuesComponent->setResourceRegistry($resourceRegistry);

        $this->assertNotNull($valuesComponent->getResourceRegistry());
        $this->assertNotNull($valuesComponent->optionValues());
        $this->assertEquals(Select::DATA_SRC_VALUES, $valuesComponent->dataSource());

//        Ensure that activateResources() was not called
        $this->expectException(\Error::class);
        $resourceRegistry->registered();
    }

    /**
     * @covers ::setResourceRegistry
     * @covers ::initSrcResources
     * @covers ::activateResources
     */
    public function testSetResourceRegistry_resourcesDataSrc(): void
    {
        $resourcesComponent = new Select(
            'test',
            'Test',
            null,
            [],
            [],
            false,
            [],
            null,
            'mixed',
            null,
            null,
            [
                'dataSrc' => Select::DATA_SRC_RESOURCE,
                'data' => [
                    'resource' => TestResource::INDEX_NAME,
                    'values' => [
                        ['label' => 'Foo', 'value' => 'foo'],
                        ['label' => 'Bar', 'value' => 'bar'],
                        ['label' => 'Number', 'value' => '1', 'shortcut' => ''],
                        ['label' => 'Notrim ', 'value' => 'Notrim ', 'shortcut' => ''],
                    ],
                ],
            ]
        );
        $resourceRegistry = $this->app->make(ResourceRegistry::class);
        $resourceRegistry->register(TestResource::class);
        $resourcesComponent->setResourceRegistry($resourceRegistry);

        $this->assertArrayHasKey(TestResource::INDEX_NAME, $resourceRegistry->registered());
        $this->assertNotNull($resourcesComponent->getResourceRegistry());
        $this->assertEquals(Select::DATA_SRC_RESOURCE, $resourcesComponent->dataSource());
    }
}

class TestResource implements ResourceInterface
{
    const INDEX_NAME = 'test';

    public static function indexName(): string
    {
        return self::INDEX_NAME;
    }

    public static function components(): array
    {
        return ['test'];
    }

    public static function submissions(int $limit, int $skip, string $key, string $needle): array
    {
        return ['test' => 'test'];
    }

    public static function handlesPaginationAndSearch(): bool
    {
        return false;
    }
}
