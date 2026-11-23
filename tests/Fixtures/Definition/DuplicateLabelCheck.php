<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Fixtures\Definition;

use Northwestern\SysDev\DynamicForms\Forms\Definition\Checks\FormDefinitionCheck;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionCheckResult;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssue;
use Northwestern\SysDev\DynamicForms\Forms\Form;

/**
 * Errors on components that reuse a label from an earlier component in the form.
 */
final class DuplicateLabelCheck implements FormDefinitionCheck
{
    public function check(Form $form): DefinitionCheckResult
    {
        $seen = [];
        $issues = [];

        foreach ($form->flatComponents() as $component) {
            $label = $component->label();
            if ($label === null || $label === '') {
                continue;
            }

            if (isset($seen[$label])) {
                $issues[] = new DefinitionIssue(
                    componentKey: $component->key(),
                    componentLabel: $label,
                    jsonPath: 'label',
                    type: CustomIssueType::AppError,
                );
            }

            $seen[$label] = true;
        }

        return new DefinitionCheckResult($issues);
    }
}
