<?php

namespace Northwestern\SysDev\DynamicForms\Forms;

final readonly class FormWarnings
{
    /**
     * @param array<string, FormWarning> $warnings
     */
    public function __construct(private array $warnings = [])
    {
    }

    /**
     * @return array<string, FormWarning>
     */
    public function all(): array
    {
        return $this->warnings;
    }

    public function isEmpty(): bool
    {
        return $this->warnings === [];
    }
}
