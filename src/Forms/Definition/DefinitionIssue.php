<?php

namespace Northwestern\SysDev\DynamicForms\Forms\Definition;

final readonly class DefinitionIssue
{
    /**
     * @param  string|null  $componentKey  The component with the issue, or null if the issue is with the whole form.
     */
    public function __construct(
        public ?string $componentKey,
        private ?string $componentLabel,
        public string $jsonPath,
        public DefinitionIssueTypeInterface $type,
        public ?string $details = null,
    ) {
    }

    /**
     * Make an issue with the whole form, instead of a specific component.
     */
    public static function forForm(string $jsonPath, DefinitionIssueTypeInterface $type, ?string $details = null): self
    {
        return new self(
            componentKey: null,
            componentLabel: null,
            jsonPath: $jsonPath,
            type: $type,
            details: $details,
        );
    }

    /**
     * Whether the issue is with the whole form, instead of a specific component.
     */
    public function isFormIssue(): bool
    {
        return $this->componentKey === null;
    }

    /**
     * The component's human-friendly label without the key, or null for form issues.
     */
    public function componentLabel(): ?string
    {
        if ($this->componentKey === null) {
            return null;
        }

        return $this->componentLabel === null || $this->componentLabel === ''
            ? $this->componentKey
            : $this->componentLabel;
    }

    /**
     * The component's label and key, e.g. "Class Year (class)", or only the key if the component has no label.
     *
     * Null for form issues.
     */
    public function componentDisplayName(): ?string
    {
        if ($this->componentKey === null) {
            return null;
        }

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
