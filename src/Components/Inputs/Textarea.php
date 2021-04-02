<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Support\Arr;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;

class Textarea extends Textfield
{
    const TYPE = 'textarea';

    /** * @var string Unsupported ACE editor */
    const EDITOR_ACE = 'ace';

    /** * @var string Unsupported Quill editor */
    const EDITOR_QUILL = 'quill';

    /** @var string */
    const EDITOR_CKEDITOR = 'ckeditor';

    /** @var string[] Supported editors */
    const SUPPORTED_EDITORS = [
        self::EDITOR_CKEDITOR,
    ];

    public function __construct(string $key, ?string $label, array $components, array $validations, bool $hasMultipleValues, array $additional)
    {
        parent::__construct($key, $label, $components, $validations, $hasMultipleValues, $additional);

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
