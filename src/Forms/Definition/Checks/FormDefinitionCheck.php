<?php

namespace Northwestern\SysDev\DynamicForms\Forms\Definition\Checks;

use Northwestern\SysDev\DynamicForms\DefinitionCheckRegistry;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionCheckResult;
use Northwestern\SysDev\DynamicForms\Forms\Form;

/**
 * A definition check that runs once against the whole form, for problems that span components, e.g. duplicate keys.
 *
 * Register it with the {@see DefinitionCheckRegistry}.
 */
interface FormDefinitionCheck
{
    public function check(Form $form): DefinitionCheckResult;
}
