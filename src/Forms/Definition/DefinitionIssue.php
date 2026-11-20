<?php

namespace Northwestern\SysDev\DynamicForms\Forms\Definition;

final readonly class DefinitionIssue
{
    public function __construct(
        public string $componentKey,
        public string $jsonPath,
        public DefinitionIssueType $type,
        public ?string $details = null,
    ) {
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
