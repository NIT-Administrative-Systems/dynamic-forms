<?php

namespace Northwestern\SysDev\DynamicForms;

use Illuminate\Support\Arr;
use Northwestern\SysDev\DynamicForms\Errors\UnknownResourceError;
use Northwestern\SysDev\DynamicForms\Resources\ResourceInterface;

class ResourceRegistry
{
    protected array $resources;

    public function __construct()
    {
    }

    /**
     * Get the registered components.
     *
     * @return ResourceInterface[] Associative array, keyed by the component's type
     */
    public function registered(): array
    {
        return $this->resources;
    }

    /**
     * Get the class name for a component.
     */
    public function get(string $type): string
    {
        if (! Arr::has($this->resources, $type)) {
            throw new UnknownResourceError($type);
        }

        return $this->resources[$type];
    }

    /**
     * Registers a resource class.
     *
     * @param ResourceInterface $resource
     */
    public function register(ResourceInterface $resource): void
    {
        $this->resources[$resource::indexName()] = $resource;
    }
}
