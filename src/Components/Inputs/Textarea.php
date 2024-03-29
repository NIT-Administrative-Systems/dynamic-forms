<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Support\Arr;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;

class Textarea extends Textfield
{
    const TYPE = 'textarea';

    /** * @var string Unsupported ACE editor */
    const EDITOR_ACE = 'ace';

    /** * @var string Supported Quill editor */
    const EDITOR_QUILL = 'quill';

    /** @var string Unsupported CKEditor editor */
    const EDITOR_CKEDITOR = 'ckeditor';

    /** @var string[] Supported editors */
    const SUPPORTED_EDITORS = [
        self::EDITOR_QUILL,
    ];

    public function __construct(
        string $key,
        ?string $label,
        ?string $errorLabel,
        array $components,
        array $validations,
        bool $hasMultipleValues,
        ?array $conditional,
        ?string $customConditional,
        string $case,
        null|array|string $calculateValue,
        mixed $defaultValue,
        array $additional
    ) {
        parent::__construct($key, $label, $errorLabel, $components, $validations, $hasMultipleValues, $conditional, $customConditional, $case, $calculateValue, $defaultValue, $additional);

        if (! Arr::get($this->additional, 'editor')) {
            Arr::set($this->additional, 'editor', self::EDITOR_QUILL);
        }

        $editor = Arr::get($this->additional, 'editor');

        if (! in_array($editor, self::SUPPORTED_EDITORS)) {
            $message = sprintf(
                'Unsupported editor "%s", must be [%s]',
                $editor,
                implode(', ', self::SUPPORTED_EDITORS)
            );

            throw new InvalidDefinitionError($message, 'editor');
        }
    }
}
