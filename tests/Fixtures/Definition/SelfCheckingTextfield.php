<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Fixtures\Definition;

use Northwestern\SysDev\DynamicForms\Components\Inputs\Textfield;
use Northwestern\SysDev\DynamicForms\Forms\Definition\CheckedFormDefinition;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssue;

/**
 * A custom component that implements its own definition check, which always warns.
 */
final class SelfCheckingTextfield extends Textfield
{
    const TYPE = 'selfcheckingtextfield';

    public function checkDefinition(): CheckedFormDefinition
    {
        return new CheckedFormDefinition([new DefinitionIssue(
            componentKey: $this->key(),
            componentLabel: $this->label(),
            jsonPath: 'type',
            type: CustomIssueType::AppWarning,
        )]);
    }
}
