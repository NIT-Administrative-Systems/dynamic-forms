<?php

namespace Northwestern\SysDev\DynamicForms;

use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag;
use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Northwestern\SysDev\DynamicForms\Components\CustomSubcomponentDeserialization;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;

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

    protected ?array $submission;
    protected ComponentRegistry $componentRegistry;

    public function __construct(string $definitionJson, ?string $submissionJson = null)
    {
        $this->componentRegistry = new ComponentRegistry();
        $this->setDefinition($definitionJson);

        if ($submissionJson) {
            $this->setSubmission($submissionJson);
        }
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

    public function setSubmission(string $json): void
    {
        $this->submission = json_decode($json, true);
    }

    /**
     * @param string|null $submissionJson Submission JSON doc, so you don't have to setSubmission() separately
     */
    public function validate(?string $submissionJson = null): MessageBag
    {
        if ($submissionJson !== null) {
            $this->setSubmission($submissionJson);
        }

        if (! $this->components || ! $this->submission) {
            // throw something, this ain't ready
        }

        // Filter down to only keys we know about, so we can behave like $request->validate()
        $components = $this->flatComponents();
        $data = collect($this->submission)->only(array_keys($components));
        $data->each(fn ($value, $key) => $components[$key]->setSubmissionValue($value));

        $overallBag = new MessageBag;
        // $processedData = [];
        foreach ($components as $component) {
            $overallBag->merge($component->validate());
            // $processedData[$component->key()] = ;
        }

        return $overallBag;
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
                $children,
                Arr::get($definition, 'validate', []),
                Arr::get($definition, 'multiple', false),
                Arr::except($definition, ['key', 'label', 'components', 'validate', 'type', 'input', 'tableView']),
            );

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
