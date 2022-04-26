<?php

namespace Northwestern\SysDev\DynamicForms\Forms\Concerns;

use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;

trait HandlesTree
{
    /**
     * Flattens the components prop into a Component key-indexed array.
     *
     * Form.io will not nest form elements (e.g. <input name="foo[bar]">), so popping it out into this flat structure
     * indexed by the Component key isn't going to cause problems.
     *
     * @param array $componentsTree
     * @return array
     */
    protected function flattenComponents(array $componentsTree): array
    {
        $flat = [];

        /** @var ComponentInterface $component */
        foreach ($componentsTree as $component) {
            $flat[$component->key()] = $component;
            $flat = array_merge($flat, $this->flattenComponents($component->components()));
        }

        return $flat;
    }
}
