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
        array $additional
    ) {
        parent::__construct($key, $label, $errorLabel, $components, $validations, $hasMultipleValues, $conditional, $customConditional, $case, $calculateValue, $defaultValue, $additional);

        // formiojs omits the dataSrc prop when it's 'values'; assume that's the mode when not present
        $this->dataSource = Arr::get($this->additional, 'dataSrc', self::DATA_SRC_VALUES);

//        match ($this->dataSource) {
//            self::DATA_SRC_VALUES => $this->initSrcValues($additional),
//            self::DATA_SRC_RESOURCE => $this->initSrcResources($additional),
//            default => $this->initSrcUnsupported(),
//        };
    }

    public function setOptionValues(): void
    {
        match ($this->dataSource) {
            self::DATA_SRC_VALUES => $this->initSrcValues($this->additional),
            self::DATA_SRC_RESOURCE => $this->initSrcResources($this->additional),
            default => $this->initSrcUnsupported(),
        };
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
                $value[$i] = (string) $singleValue;
            }

            return $value;
        }

        return is_scalar($value) ? (string) $value : $value;
    }

//    TODO: a select with no values will error out
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

    public function optionValues(): array
    {
        return $this->optionValues;
    }

    private function initSrcValues(array $additional): void
    {
        $this->optionValues = collect($this->additional['data']['values'])
            ->map(function (array $pair) {
                return trim(Arr::get($pair, 'value'));
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
    }
}
