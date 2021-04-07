<?php

namespace Northwestern\SysDev\DirectoryLookupComponent;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\SOA\DirectorySearch;

class DirectoryLookup extends BaseComponent
{
    const TYPE = 'nuDirectoryLookup';

    protected DirectorySearch $api;

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

        $this->setDirectorySearch(app()->make(DirectorySearch::class));
    }

    public function setDirectorySearch(DirectorySearch $api)
    {
        $this->api = $api;
    }

    protected function processValidations(string $fieldKey, mixed $submissionValue, Factory $validator): MessageBag
    {
        $singleFieldRules = ['nullable', 'string'];
        if ($this->validation('required')) {
            $singleFieldRules = ['string', 'required'];
        }

        $rules = [
            'display' => $singleFieldRules,
            'searchType' => array_merge($singleFieldRules, ['in:netid,email,emplid']),
            'person.netid' => $singleFieldRules,
            'person.email' => $singleFieldRules,
            'person.name' => $singleFieldRules,
        ];

        // Make sure the fields we expect to find are actually here
        $errorBag = $validator->make($submissionValue, $rules)->messages();

        if (! $errorBag->isEmpty()) {
            return $errorBag;
        }

        // Don't try to validate against DS if the person isn't filled in
        if (! Arr::get($submissionValue, 'person')) {
            return $errorBag;
        }

        $directory = $this->api->lookup($submissionValue['display'], $submissionValue['searchType'], 'basic');
        if (! $directory) {
            $errorBag->add('display', 'Person not found in directory.');

            return $errorBag;
        }

        if (
            $directory['uid'] != $submissionValue['person']['netid']
            || $directory['mail'] != $submissionValue['person']['email']
            || $directory['displayName'][0] != $submissionValue['person']['name']
            || $directory['nuAllTitle'][0] != $submissionValue['person']['title']
        ) {
            $errorBag->add('display', 'Internal error: directory data mismatch');
        }

        return $errorBag;
    }
}
