<?php

namespace Northwestern\SysDev\DirectoryLookupComponent\Tests;

use Northwestern\SysDev\DirectoryLookupComponent\DirectoryLookup;
use Northwestern\SysDev\DynamicForms\Tests\Components\InputComponentTestCase;
use Northwestern\SysDev\SOA\DirectorySearch;

class DirectoryLookupTest extends InputComponentTestCase
{
    public string $componentClass = DirectoryLookup::class;

    public function validationsProvider(): array
    {
        $valid = [
            'display' => 'test',
            'searchMode' => 'netid',
            'person' => [
                'netid' => 'test',
                'email' => 'test@example.org',
                'name' => 'Steve Standardstone',
                'title' => 'Petrologist',
            ],
        ];

        return [
            'no data passes' => [[], ['display' => ''], true],
            'valid data passes' => [[], $valid, true],
            'invalid data fails' => [[], array_merge_recursive($valid, ['person' => ['netid' => 'dog']]), false],
            'required passes' => [['required' => true], $valid, true],
            'required fails' => [['required' => true], ['display' => 'xx'], false],
        ];
    }

    protected function getComponent(
        string $key = 'test',
        ?string $label = 'Test',
        array $components = [],
        array $validations = [],
        ?array $additional = [],
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
        $component = parent::getComponent($key, $label, $components, $validations, $additional, $submissionValue);
        $component->setDirectorySearch($apiStub);

        return $component;
    }
}
