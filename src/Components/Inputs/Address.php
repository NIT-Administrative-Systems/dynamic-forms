<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;

class Address extends BaseComponent
{
    const TYPE = 'address';

    const PROVIDER_GOOGLE = 'google';
    const PROVIDER_AZURE = 'azure';
    const PROVIDER_CUSTOM = 'custom';
    const PROVIDER_OPENSTREETMAP = 'nominatim';

    const SUPPORTED_PROVIDERS = [
        self::PROVIDER_OPENSTREETMAP,
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
        array $additional
    ) {
        // Components are discarded; these are manual mode fields, which is not supported.
        parent::__construct($key, $label, $errorLabel, [], $validations, $hasMultipleValues, $conditional, $customConditional, $case, $additional);

        $provider = Arr::get($this->additional, 'provider');
        if (! in_array($provider, self::SUPPORTED_PROVIDERS)) {
            $message = sprintf(
                'Unsupported provider "%s", must be [%s]',
                $provider,
                implode(', ', self::SUPPORTED_PROVIDERS)
            );

            throw new InvalidDefinitionError($message, 'provider');
        }
    }

    protected function processValidations(string $fieldKey, mixed $submissionValue, Factory $validator): MessageBag
    {
        // This isn't a scalar, so our typical RuleBag pattern does not work here.

        $rules = [];
        if ($this->validation('required')) {
            $rules["$fieldKey.display_name"] = 'string|required';
            $rules["$fieldKey.address"] = 'present';
        }

        return $validator->make(
            [$fieldKey => $submissionValue],
            $rules,
        )->messages();
    }
}
