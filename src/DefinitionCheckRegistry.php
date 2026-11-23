<?php

namespace Northwestern\SysDev\DynamicForms;

use InvalidArgumentException;
use Northwestern\SysDev\DynamicForms\Forms\Definition\Checks\ComponentDefinitionCheck;
use Northwestern\SysDev\DynamicForms\Forms\Definition\Checks\FormDefinitionCheck;
use Northwestern\SysDev\DynamicForms\Forms\Definition\Checks\JsonValidationCheck;
use Northwestern\SysDev\DynamicForms\Forms\Form;

/**
 * Checks that {@see Form::checkDefinition()} runs in addition to each component's own checks.
 */
class DefinitionCheckRegistry
{
    /**
     * @var list<ComponentDefinitionCheck|FormDefinitionCheck|class-string<ComponentDefinitionCheck|FormDefinitionCheck>>
     */
    protected array $checks = [];

    public function __construct()
    {
        $this->registerDefaults();
    }

    /**
     * Register a check.
     *
     * A class name will be resolved from the Laravel container when the checks run, so its constructor can have
     * dependencies injected.
     *
     * @param  ComponentDefinitionCheck|FormDefinitionCheck|class-string<ComponentDefinitionCheck|FormDefinitionCheck>  $check
     *
     * @throws InvalidArgumentException The class does not implement either check interface.
     */
    public function register(ComponentDefinitionCheck|FormDefinitionCheck|string $check): void
    {
        if (is_string($check) && ! $this->isCheck($check)) {
            throw new InvalidArgumentException(sprintf(
                '%s must implement %s or %s.',
                $check,
                ComponentDefinitionCheck::class,
                FormDefinitionCheck::class,
            ));
        }

        $this->checks[] = $check;
    }

    /**
     * @return list<ComponentDefinitionCheck>
     */
    public function componentChecks(): array
    {
        return array_values(array_filter(
            $this->resolved(),
            fn (object $check) => $check instanceof ComponentDefinitionCheck,
        ));
    }

    /**
     * @return list<FormDefinitionCheck>
     */
    public function formChecks(): array
    {
        return array_values(array_filter(
            $this->resolved(),
            fn (object $check) => $check instanceof FormDefinitionCheck,
        ));
    }

    /**
     * @return list<ComponentDefinitionCheck|FormDefinitionCheck>
     */
    protected function resolved(): array
    {
        return array_map(
            fn (object|string $check) => is_string($check) ? resolve($check) : $check,
            $this->checks,
        );
    }

    protected function isCheck(string $class): bool
    {
        return is_a($class, ComponentDefinitionCheck::class, true)
            || is_a($class, FormDefinitionCheck::class, true);
    }

    private function registerDefaults(): void
    {
        $this->register(JsonValidationCheck::class);
    }
}
