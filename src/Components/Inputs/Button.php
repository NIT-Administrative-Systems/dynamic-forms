<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Support\Arr;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;

class Button extends BaseComponent
{
    const TYPE = 'button';

    public const ACTION_SUBMIT = 'submit';
    public const ACTION_SAVE_STATE = 'saveState';
    public const ACTION_EVENT = 'event';
    public const ACTION_CUSTOM = 'custom';
    public const ACTION_RESET = 'reset';
    public const ACTION_OAUTH = 'oauth';
    public const ACTION_POST_TO_URL = 'url';

    protected string $action;

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

        // When the key is not present, it defaults to submit.
        $this->action = Arr::get($additional, 'action', self::ACTION_SUBMIT);
    }

    public function canValidate(): bool
    {
        return false;
    }

    public function action(): string
    {
        return $this->action;
    }
}
