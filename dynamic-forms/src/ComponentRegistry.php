<?php

namespace Northwestern\SysDev\DynamicForms;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Northwestern\SysDev\DynamicForms\Errors\UnknownComponentError;
use Symfony\Component\Finder\Finder;

class ComponentRegistry
{
    protected array $components;

    public function __construct()
    {
        $this->registerDefaults();
    }

    /**
     * Get the registered components.
     *
     * @return ComponentInterface[] Associative array, keyed by the component's type
     */
    public function registered(): array
    {
        return $this->components;
    }

    /**
     * Get the class name for a component.
     */
    public function get(string $type): string
    {
        if (! Arr::has($this->components, $type)) {
            throw new UnknownComponentError($type);
        }

        return $this->components[$type];
    }

    /**
     * Registers a component class.
     *
     * @param string $component
     */
    public function register(string $component): void
    {
        $this->components[$component::type()] = $component;
    }

    /**
     * Checks the Components folder & registers all of the classes we ship with.
     *
     * @throws \ReflectionException
     */
    private function registerDefaults(): void
    {
        $files = Finder::create()
            ->in([
                __DIR__.DIRECTORY_SEPARATOR.'Components'.DIRECTORY_SEPARATOR.'Layout',
                __DIR__.DIRECTORY_SEPARATOR.'Components'.DIRECTORY_SEPARATOR.'Inputs',
            ])
            ->name('*.php')
            ->files();

        foreach ($files as $file) {
            $component = '\\'.__NAMESPACE__.'\\'.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($file->getRealPath(), __DIR__.DIRECTORY_SEPARATOR)
                );

            if (
                is_subclass_of($component, ComponentInterface::class)
                && ! (new \ReflectionClass($component))->isAbstract()
            ) {
                $this->register($component);
            }
        }
    }
}
