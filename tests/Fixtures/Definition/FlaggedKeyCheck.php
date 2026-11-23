<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Fixtures\Definition;

use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Northwestern\SysDev\DynamicForms\Forms\Definition\Checks\ComponentDefinitionCheck;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionCheckResult;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssue;

/**
 * Warns about any component whose key starts with the configured prefix.
 */
final class FlaggedKeyCheck implements ComponentDefinitionCheck
{
    public function __construct(private readonly FlaggedKeyPrefix $prefix)
    {
    }

    public function check(ComponentInterface $component): DefinitionCheckResult
    {
        if (! str_starts_with($component->key(), $this->prefix->value)) {
            return new DefinitionCheckResult;
        }

        return new DefinitionCheckResult([new DefinitionIssue(
            componentKey: $component->key(),
            componentLabel: $component->label(),
            jsonPath: 'key',
            type: CustomIssueType::AppWarning,
        )]);
    }
}
