<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Fixtures\Definition;

use Northwestern\SysDev\DynamicForms\Forms\Definition\CheckedFormDefinition;
use Northwestern\SysDev\DynamicForms\Forms\Definition\Checks\FormDefinitionCheck;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssue;
use Northwestern\SysDev\DynamicForms\Forms\Form;

/**
 * Errors if the form has no component with the "email" key, to test issues that aren't tied to a component.
 */
final class RequiresEmailCheck implements FormDefinitionCheck
{
    public function check(Form $form): CheckedFormDefinition
    {
        if (array_key_exists('email', $form->flatComponents())) {
            return new CheckedFormDefinition;
        }

        return new CheckedFormDefinition([
            DefinitionIssue::forForm('components', CustomIssueType::AppError, 'Add an "email" component.'),
        ]);
    }
}
