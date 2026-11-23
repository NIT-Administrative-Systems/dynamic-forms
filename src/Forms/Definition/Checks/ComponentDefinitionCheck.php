<?php

namespace Northwestern\SysDev\DynamicForms\Forms\Definition\Checks;

use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Northwestern\SysDev\DynamicForms\DefinitionCheckRegistry;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionCheckResult;

/**
 * A definition check that runs against every component in a form, regardless of the component's type.
 *
 * Register it with the {@see DefinitionCheckRegistry}. To check something that only applies to one type of component,
 * implement {@see ComponentInterface::checkDefinition()} on that component instead.
 */
interface ComponentDefinitionCheck
{
    public function check(ComponentInterface $component): DefinitionCheckResult;
}
