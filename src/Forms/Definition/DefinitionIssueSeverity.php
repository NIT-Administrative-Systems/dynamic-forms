<?php

namespace Northwestern\SysDev\DynamicForms\Forms\Definition;

enum DefinitionIssueSeverity: string
{
    /**
     * The form definition is broken. Submissions will fail to process.
     */
    case Error = 'error';

    /**
     * The form definition is probably wrong, but Dynamic Forms will still attempt to process submissions.
     */
    case Warning = 'warning';
}
