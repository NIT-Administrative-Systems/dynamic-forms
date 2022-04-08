<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\RuleBag;

class Day extends BaseComponent
{
    const TYPE = 'day';

    public function processValidations(string $fieldKey, string $fieldLabel, mixed $submissionValue, Factory $validator): MessageBag
    {
        $dateParts = $this->getDateParts($submissionValue);

        // required is done on each part, so this can be nullable
        $dateRules = new RuleBag($fieldKey, [
            'nullable',
            sprintf('date_format:%s', $this->makeDateFormatString($dateParts)),
        ]);
        $partsRules = []; // non-scalar, can't use the bag

        // NOTE: none of the validation rules for this component are in the validations object!
        $maxDate = Arr::get($this->additional, 'maxDate');
        $minDate = Arr::get($this->additional, 'minDate');

        $dayRequired = Arr::get($this->additional, 'day.required');
        $monthRequired = Arr::get($this->additional, 'month.required');
        $yearRequired = Arr::get($this->additional, 'year.required');

        $minYear = Arr::get($this->additional, 'year.minYear');
        $maxYear = Arr::get($this->additional, 'year.maxYear');

        $dateRules->addIfNotNull(sprintf('before_or_equal:%s', $maxDate), $maxDate);
        $dateRules->addIfNotNull(sprintf('after_or_equal:%s', $minDate), $minDate);

        if ($dayRequired) {
            $partsRules["$fieldKey.day"] = ['integer', 'required', 'between:1,31'];
        }

        if ($monthRequired) {
            $partsRules["$fieldKey.month"] = ['integer', 'required', 'between:1,12'];
        }

        if ($yearRequired) {
            $partsRules["$fieldKey.year"] = ['integer', 'required'];
        } else {
            $partsRules["$fieldKey.year"] = ['nullable', 'integer'];
        }

        if ($minYear) {
            $partsRules["$fieldKey.year"] = array_merge(Arr::get($partsRules, "$fieldKey.year", []), [sprintf('min:%s', $minYear)]);
        }

        if ($maxYear) {
            $partsRules["$fieldKey.year"] = array_merge(Arr::get($partsRules, "$fieldKey.year", []), [sprintf('max:%s', $maxYear)]);
        }

        $partsBag = $validator->make(
            [$fieldKey => $dateParts],
            $partsRules,
        )->messages();

        $dateBag = $validator->make(
            [$fieldKey => $submissionValue],
            $dateRules->rules(),
            [],
            [$fieldKey => $fieldLabel]
        )->messages();

        return $partsBag->merge($dateBag);
    }

    /**
     * @param string $value Value in mm/dd/YYYY format
     */
    protected function getDateParts(string $value): array
    {
        $value = explode('/', $value);
        $date = collect([
            'year' => Arr::get($value, 2),
            'month' => Arr::get($value, 0),
            'day' => Arr::get($value, 1),
        ]);

        return $date->map(fn ($val) => (int) $val ?: null)->all();
    }

    /**
     * Makes a format compatible w/ Illuminate Validation's date_format rule.
     */
    private function makeDateFormatString(array $parts): string
    {
        $format = [];

        // Order is significant
        $format[] = $parts['month'] ? 'm' : '00';
        $format[] = $parts['day'] ? 'd' : '00';
        $format[] = $parts['year'] ? 'Y' : '0000';

        return implode('/', $format);
    }
}
