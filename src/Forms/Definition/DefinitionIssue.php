<?php

namespace Northwestern\SysDev\DynamicForms\Forms\Definition;

final readonly class DefinitionIssue
{
    public function __construct(
        public string $componentKey,
        private ?string $componentLabel,
        public string $jsonPath,
        public DefinitionIssueType $type,
        public ?string $details = null,
    ) {
    }

    /**
     * The component's human-friendly label without the key.
     */
    public function componentLabel(): string
    {
        return $this->componentLabel === null || $this->componentLabel === ''
            ? $this->componentKey
            : $this->componentLabel;
    }

    /**
     * The component's label and key, e.g. "Class Year (class)", or only the key if the component has no label.
     */
    public function componentDisplayName(): string
    {
        return $this->componentLabel() === $this->componentKey
            ? $this->componentKey
            : "{$this->componentLabel()} ({$this->componentKey})";
    }

    public function severity(): DefinitionIssueSeverity
    {
        return $this->type->severity();
    }

    public function description(): string
    {
        return $this->type->description();
    }
}
