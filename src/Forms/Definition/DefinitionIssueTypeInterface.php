<?php

namespace Northwestern\SysDev\DynamicForms\Forms\Definition;

/**
 * A kind of problem found by a form definition check.
 *
 * Dynamic Forms' own issue types are in {@see DefinitionIssueType}. Applications with custom checks can implement this
 * interface on their own enum to define additional issue types.
 */
interface DefinitionIssueTypeInterface
{
    /**
     * Stable, machine-readable identifier for the issue type, e.g. "json_validation_missing_true".
     *
     * Applications should prefix their codes to avoid collisions with Dynamic Forms' codes.
     */
    public function code(): string;

    public function severity(): DefinitionIssueSeverity;

    /**
     * Human-readable explanation of the issue.
     */
    public function description(): string;
}
