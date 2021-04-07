<?php

namespace Northwestern\SysDev\DirectoryLookupComponent\Tests;

use Northwestern\SysDev\DirectoryLookupComponent\DirectoryLookup;
use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Tests\Components\InputComponentTestCase;
use Northwestern\SysDev\SOA\DirectorySearch;

class DirectoryLookupTest extends InputComponentTestCase
{
    public string $componentClass = DirectoryLookup::class;

    const VALID_DATA = [
        'display' => 'test',
        'searchType' => 'netid',
        'person' => [
            'netid' => 'test',
            'email' => 'test@example.org',
            'name' => 'Steve Standardstone',
            'title' => 'Petrologist',
        ],
    ];

    public function validationsProvider(): array
    {
        return [
            'no data passes' => [[], ['display' => ''], true],
            'valid data passes' => [[], self::VALID_DATA, true],
            'invalid data fails' => [[], array_merge_recursive(self::VALID_DATA, ['person' => ['netid' => 'dog']]), false],
            'required passes' => [['required' => true], self::VALID_DATA, true],
            'required fails' => [['required' => true], ['display' => 'xx'], false],
        ];
    }

    public function submissionValueProvider(): array
    {
        return [
            'no transformations' => [null, self::VALID_DATA, self::VALID_DATA],
            'upper' => [CaseEnum::UPPER, self::VALID_DATA, self::VALID_DATA],
            'lower' => [CaseEnum::LOWER, self::VALID_DATA, self::VALID_DATA],
        ];
    }

    protected function getComponent(
        string $key = 'test',
        ?string $label = 'Test',
        ?string $errorLabel = null,
        array $components = [],
        array $validations = [],
        ?array $additional = [],
        bool $hasMultipleValues = false,
        ?array $conditional = null,
        ?string $customConditional = null,
        string $case = 'mixed',
        mixed $submissionValue = null,
    ): DirectoryLookup {
        $apiStub = $this->createStub(DirectorySearch::class);
        $apiStub->method('lookup')->willReturn([
            'uid' => 'test',
            'mail' => 'test@example.org',
            'displayName' => ['Steve Standardstone'],
            'nuAllTitle' => ['Petrologist'],
        ]);

        /** @var DirectoryLookup $component */
        $component = parent::getComponent(
            $key,
            $label,
            $errorLabel,
            $components,
            $validations,
            $additional,
            $hasMultipleValues,
            $conditional,
            $customConditional,
            $case,
            $submissionValue
        );
        $component->setDirectorySearch($apiStub);

        return $component;
    }
}
