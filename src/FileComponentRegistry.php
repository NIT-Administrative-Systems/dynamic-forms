<?php

namespace Northwestern\SysDev\DynamicForms;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Northwestern\SysDev\DynamicForms\Errors\UnknownStorageDriverError;
use Northwestern\SysDev\DynamicForms\Storage\StorageInterface;
use Symfony\Component\Finder\Finder;

class FileComponentRegistry
{
    protected array $storageDrivers;

    public function __construct()
    {
        $this->registerDefaults();
    }

    /**
     * Get the registered components.
     *
     * @return StorageInterface[] Associative array, keyed by the storage type
     */
    public function registered(): array
    {
        return $this->storageDrivers;
    }

    /**
     * Get the storaage driver name for a given storage Type.
     */
    public function get(string $type): string
    {
        if (! Arr::has($this->storageDrivers, $type)) {
            throw new UnknownStorageDriverError($type);
        }

        return $this->storageDrivers[$type];
    }

    /**
     * Registers a component class.
     *
     * @param string $interface
     */
    public function register(string $interface): void
    {
        $this->storageDrivers[$interface::getStorageMethod()] = $interface;
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
                __DIR__.DIRECTORY_SEPARATOR.'Storage',
            ])
            ->name('*.php')
            ->files();

        foreach ($files as $file) {
            $interface = '\\'.__NAMESPACE__.'\\'.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($file->getRealPath(), __DIR__.DIRECTORY_SEPARATOR)
                );

            if (
                is_subclass_of($interface, StorageInterface::class)
                && ! (new \ReflectionClass($interface))->isAbstract()
            ) {
                $this->register($interface);
            }
        }
    }
}
