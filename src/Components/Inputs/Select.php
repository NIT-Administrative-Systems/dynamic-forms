<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Rule;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\Components\ResourceValues;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;
use Northwestern\SysDev\DynamicForms\Errors\UnknownResourceError;
use Northwestern\SysDev\DynamicForms\ResourceRegistry;
use Northwestern\SysDev\DynamicForms\RuleBag;

class Select extends BaseComponent implements ResourceValues
{
    const TYPE = 'select';
    protected ResourceRegistry $resourceRegistry;

    const DATA_SRC_VALUES = 'values';
    const DATA_SRC_URL = 'url';
    const DATA_SRC_RESOURCE = 'resource';
    const DATA_SRC_CUSTOM = 'custom';
    const DATA_SRC_JSON = 'json';
    const DATA_SRC_INDEXED_DB = 'indexeddb';

    /**
     * Supported Data -> Source values.
     *
     * @var string[]
     */
    const SUPPORTED_DATA_SRC = [
        self::DATA_SRC_VALUES,
        self::DATA_SRC_RESOURCE,
    ];

    protected string $dataSource;

    /**
     * Valid values from the definition, for DATA_SRC_VALUES mode.
     */
    protected array $optionValues;
    protected ?array $optionValuesWithLabels = null;

    /**
     * Valid resources from the definition, for DATA_SRC_RESOURCE mode.
     */
    protected array $optionResources;

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
        array $additional,
    ) {
        parent::__construct($key, $label, $errorLabel, $components, $validations, $hasMultipleValues, $conditional, $customConditional, $case, $calculateValue, $defaultValue, $additional);

        // formiojs omits the dataSrc prop when it's 'values'; assume that's the mode when not present
        $this->dataSource = Arr::get($this->additional, 'dataSrc', self::DATA_SRC_VALUES);

        match ($this->dataSource) {
            self::DATA_SRC_VALUES => $this->initSrcValues(),
            default => $this->initSrcOther(),
        };
    }

    /**
     * This function must be run during component or form instantiation. Allows user's application to use the resources they've registered.
     */
    public function activateResources(): void
    {
        $this->initSrcResources($this->additional, $this->resourceRegistry);
    }

    /**
     * {@inheritDoc}
     */
    public function submissionValue(): mixed
    {
        $value = parent::submissionValue();

        if ($this->hasMultipleValues()) {
            $value ??= [];

            foreach ($value as $i => $singleValue) {
                if (is_array($singleValue)) {
                    $value[$i] = $singleValue;
                } else {
                    $value[$i] = (string) $singleValue;
                }
            }

            return $value;
        }

        return is_scalar($value) ? (string) $value : $value;
    }

    // TODO: a select with no values will error out
    protected function processValidations(string $fieldKey, string $fieldLabel, mixed $submissionValue, Factory $validator): MessageBag
    {
        $rules = new RuleBag($fieldKey, ['string']);

        // Required if that's selected, otherwise mark it optional so Rule::in() won't fail it
        $rules->addIfNotNull('required', $this->validation('required'));
        $rules->addIf('nullable', ! $this->validation('required'));

        $rules->addIf(Rule::in($this->optionValues()), $this->dataSource === self::DATA_SRC_VALUES);

        return $validator->make(
            [$fieldKey => $submissionValue],
            $rules->rules(),
            [],
            [$fieldKey => $fieldLabel]
        )->messages();
    }

    public function dataSource(): string
    {
        return $this->dataSource;
    }

    /**
     * List of valid option values.
     *
     * Will be empty when the data source is not {@see Select::DATA_SRC_VALUES}.
     */
    public function optionValues(): array
    {
        return $this->optionValues;
    }

    /**
     * Values and their corresponding labels.
     *
     * Will be null when the datasource is not {@see Select::DATA_SRC_VALUES}.
     */
    public function options(): ?array
    {
        return $this->optionValuesWithLabels;
    }

    private function initSrcValues(): void
    {
        $options = collect(Arr::get($this->additional, 'data.values', []));

        $this->optionValues = $options
            ->map(function (array $pair) {
                return trim(Arr::get($pair, 'value'));
            })
            ->all();

        $this->optionValuesWithLabels = $options
            ->mapWithKeys(function (array $pair) {
                $value = trim(Arr::get($pair, 'value'));
                $label = trim(Arr::get($pair, 'label'));

                return [$value => $label];
            })
            ->all();
    }

    private function initSrcResources(array $additional, ResourceRegistry $resourceRegistry): void
    {
        //add in stuff for valueProperty
        $resourceList = $resourceRegistry->registered();
        $resource = $additional['data']['resource'];

        if (! isset($resourceList[$resource])) {
            throw new UnknownResourceError($resource);
        }

        $this->optionValues = collect($resourceList[$resource]::submissions(-1, 0, '', ''))->transform(function ($val) {
            return json_encode($val);
        })->all();
    }

    private function initSrcOther(): void
    {
        if ($this->dataSource == self::DATA_SRC_RESOURCE) {
            // This is left blank because initSrcResources cannot be called in the constructor
            // because resourceRegistry is initialized after calling the component's constructor in Form.php
        } else {
            $this->initSrcUnsupported(); // we still want to catch unsupported data sources
        }
    }

    private function initSrcUnsupported(): void
    {
        throw new InvalidDefinitionError(
            sprintf('Unsupported dataSrc "%s", must be [%s]', $this->dataSource, implode(', ', self::SUPPORTED_DATA_SRC)),
            'dataSrc'
        );
    }

    public function transformations(): array
    {
        // Field does not support transformations
        return [];
    }

    public function getResourceRegistry(): ResourceRegistry
    {
        return $this->resourceRegistry;
    }

    public function setResourceRegistry(ResourceRegistry $resourceRegistry): void
    {
        $this->resourceRegistry = $resourceRegistry;
        if ($this->dataSource() === self::DATA_SRC_RESOURCE) {
            $this->activateResources();
        }
    }
}
