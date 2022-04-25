<?php

namespace Northwestern\SysDev\DynamicForms\Forms;

use Illuminate\Support\Arr;
use Northwestern\SysDev\DynamicForms\ComponentRegistry;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Northwestern\SysDev\DynamicForms\Components\CustomSubcomponentDeserialization;
use Northwestern\SysDev\DynamicForms\Components\UploadInterface;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;
use Northwestern\SysDev\DynamicForms\FileComponentRegistry;

class Form
{
    /**
     * Array of components, potentially with more components nested inside of those.
     *
     * @var ComponentInterface[]
     */
    protected array $components;

    /**
     * Components w/ nesting flattened out, indexed by the component key.
     *
     * @var ComponentInterface[]
     */
    protected array $flatComponents;

    protected ComponentRegistry $componentRegistry;

    protected FileComponentRegistry $fileComponentRegistry;

    public function __construct(string $definitionJson)
    {
        $this->componentRegistry = resolve(ComponentRegistry::class);
        $this->fileComponentRegistry = resolve(FileComponentRegistry::class);
        $this->setDefinition($definitionJson);
    }

    /**
     * Get the nested components.
     *
     * @return ComponentInterface[]
     */
    public function components(): array
    {
        return $this->components;
    }

    /**
     * Get a flat array of key: components back.
     *
     * @return ComponentInterface[]
     */
    public function flatComponents(): array
    {
        return $this->flatComponents;
    }

    /**
     * Runs validations & transformations, returning a ValidatedForm object.
     */
    public function validate(string $submissionJson): ValidatedForm
    {
        return new ValidatedForm($this->flatComponents, json_decode($submissionJson, true));
    }

    protected function setDefinition(string $json): void
    {
        $json = json_decode($json, true);
        if (! Arr::has($json, 'components')) {
            throw new InvalidDefinitionError('Expected path missing', 'components');
        }

        $this->components = $this->processComponentDefinition($json);
        $this->flatComponents = $this->flattenComponents($this->components);
    }

    /**
     * Recursive function to deserialize all of the components into Component objects.
     */
    protected function processComponentDefinition(array $componentJson, $path = ''): array
    {
        if (! Arr::has($componentJson, 'components')) {
            return [];
        }

        $components = [];
        foreach ($componentJson['components'] as $definition) {
            if (! Arr::has($definition, ['key', 'type'])) {
                $path .= '.components';
                throw new InvalidDefinitionError('Unable to find required component props "key" & "type"', $path);
            }

            $class = $this->componentRegistry->get($definition['type']);

            // Some components (columns + tables) don't keep children in 'components' like they ought to
            if (is_subclass_of($class, CustomSubcomponentDeserialization::class)) {
                $children = $this->getCustomChildren($class, $definition, $path);
            } else {
                $children = $this->processComponentDefinition($definition, $path.'.'.$definition['key'].'.components');
            }

            $component = new $class(
                $definition['key'],
                Arr::get($definition, 'label'),
                Arr::get($definition, 'errorLabel'),
                $children,
                Arr::get($definition, 'validate', []),
                Arr::get($definition, 'multiple', false),
                Arr::get($definition, 'conditional'),
                Arr::get($definition, 'customConditional'),
                Arr::get($definition, 'case', 'mixed'),
                Arr::get($definition, 'calculateValue'),
                Arr::get($definition, 'defaultValue'),
                Arr::except($definition, ['key', 'label', 'components', 'validate', 'type', 'input', 'tableView', 'multiple', 'conditional', 'customConditional', 'calculateValue', 'case', 'errorLabel', 'defaultValue']),
            );

            if (is_subclass_of($component, UploadInterface::class)) {
                $storageDriver = $this->fileComponentRegistry->get($component->getStorageType());
                $component->setStorageDriver(resolve($storageDriver));
            }

            $components[] = $component;
        }

        return $components;
    }

    /**
     * @param string $class
     * @param array $definition
     * @param string $basePath
     * @return BaseComponent[]
     * @throws InvalidDefinitionError
     */
    private function getCustomChildren(string $class, array $definition, string $basePath): array
    {
        $paths = $class::pathsToChildren($definition);

        $children = collect();
        foreach ($paths as $path) {
            $children = $children->concat($this->processComponentDefinition(Arr::get($definition, $path), $basePath.''.$path));
        }

        return $children->all();
    }

    /**
     * Flattens the components prop into a Component key-indexed array.
     *
     * As far as I am aware, Form.io will NOT nest form elements (e.g. <input name="foo[bar]">),
     * so popping it out into this flat structure indexed by the Component key isn't going to
     * cause problems.
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
