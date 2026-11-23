<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Fixtures\Definition;

use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssueSeverity;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssueTypeInterface;

/**
 * An application-defined issue type, for testing extensibility.
 */
enum CustomIssueType: string implements DefinitionIssueTypeInterface
{
    case AppError = 'app_error';
    case AppWarning = 'app_warning';

    public function code(): string
    {
        return "app.{$this->value}";
    }

    public function severity(): DefinitionIssueSeverity
    {
        return match ($this) {
            self::AppError => DefinitionIssueSeverity::Error,
            self::AppWarning => DefinitionIssueSeverity::Warning,
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::AppError => 'Application error.',
            self::AppWarning => 'Application warning.',
        };
    }
}
