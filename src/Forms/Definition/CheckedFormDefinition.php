<?php

namespace Northwestern\SysDev\DynamicForms\Forms\Definition;

/**
 * Results of checking a form definition for problems.
 *
 * Issues with {@see DefinitionIssueSeverity::Error} severity will cause submissions to fail. Issues with
 * {@see DefinitionIssueSeverity::Warning} severity are probably mistakes, but submissions will still be processed.
 */
final readonly class CheckedFormDefinition
{
    /**
     * @var list<DefinitionIssue>
     */
    private array $issues;

    /**
     * @param  list<DefinitionIssue>  $issues
     */
    public function __construct(array $issues = [])
    {
        $this->issues = array_values($issues);
    }

    /**
     * @return list<DefinitionIssue>
     */
    public function all(): array
    {
        return $this->issues;
    }

    /**
     * @return list<DefinitionIssue>
     */
    public function errors(): array
    {
        return $this->withSeverity(DefinitionIssueSeverity::Error);
    }

    /**
     * @return list<DefinitionIssue>
     */
    public function warnings(): array
    {
        return $this->withSeverity(DefinitionIssueSeverity::Warning);
    }

    public function hasErrors(): bool
    {
        return $this->errors() !== [];
    }

    public function hasWarnings(): bool
    {
        return $this->warnings() !== [];
    }

    public function isClean(): bool
    {
        return $this->issues === [];
    }

    public function merge(CheckedFormDefinition $other): self
    {
        return new self([...$this->issues, ...$other->issues]);
    }

    /**
     * @return list<DefinitionIssue>
     */
    private function withSeverity(DefinitionIssueSeverity $severity): array
    {
        return array_values(array_filter(
            $this->issues,
            fn (DefinitionIssue $issue) => $issue->severity() === $severity,
        ));
    }
}
