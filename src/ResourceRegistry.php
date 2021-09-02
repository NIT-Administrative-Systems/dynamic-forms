<?php


namespace Northwestern\SysDev\DynamicForms;


use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Northwestern\SysDev\DynamicForms\Errors\UnknownResourceError;
use Northwestern\SysDev\DynamicForms\Resources\ResourceInterface;
use Symfony\Component\Finder\Finder;

class ResourceRegistry
{
    protected array $resources;

    public function __construct()
    {
        $this->registerDefaults();
    }

    /**
     * Get the registered resources.
     *
     * @return ResourceInterface[] array, keyed by the resource's index name
     */
    public function registered(): array
    {
        return $this->resources;
    }

    /**
     * Get the class name for a component.
     */
    public function get(string $indexName): string
    {
        if (! Arr::has($this->resources, $indexName)) {
            throw new UnknownResourceError($indexName);
        }

        return $this->resources[$indexName];
    }

    /**
     * Registers a component class.
     *
     * @param string $component
     */
    public function register(string $component): void
    {
        $this->resources[$component::indexName()] = $component;
    }

    /**
     * Checks the Resource folder & registers all of the classes we ship with.
     *
     * @throws \ReflectionException
     */
    private function registerDefaults(): void
    {
        $files = Finder::create()
            ->in([
                __DIR__.DIRECTORY_SEPARATOR.'Resources',
            ])
            ->name('*.php')
            ->files();

        foreach ($files as $file) {
            $resource = __NAMESPACE__.'\\'.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($file->getRealPath(), __DIR__.DIRECTORY_SEPARATOR)
                );

            if (
                is_subclass_of($resource, ResourceInterface::class)
                && ! (new \ReflectionClass($resource))->isAbstract()
            ) {
                $this->register($resource);
            }
        }
    }
}