<?php

namespace Northwestern\SysDev\DynamicForms\Forms;

final readonly class FormWarning
{
    public function __construct(
        public string $componentKey,
        public string $jsonPath,
        public FormWarningType $type,
        public ?string $details = null,
    ) {
    }
}
