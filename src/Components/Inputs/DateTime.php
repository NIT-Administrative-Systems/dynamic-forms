<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Carbon\CarbonInterface;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\RuleBag;
use Northwestern\SysDev\DynamicForms\Rules\NotWeekday;
use Northwestern\SysDev\DynamicForms\Rules\NotWeekend;

class DateTime extends BaseComponent
{
    const TYPE = 'datetime';

    /**
     * @return CarbonInterface|null|CarbonInterface[] DateTime in UTC
     */
    public function submissionValue(): CarbonInterface | null | array
    {
        $cleaner = function ($datetime) {
            if (! $datetime) {
                return null;
            }

            try {
                return (new Carbon($datetime))->utc();
            } catch (InvalidFormatException) {
                return null;
            }
        };

        return $this->hasMultipleValues()
            ? collect($this->submissionValue)->map($cleaner)->all()
            : $cleaner($this->submissionValue);
    }

    public function processValidations(string $fieldKey, string $fieldLabel, mixed $submissionValue, Factory $validator): MessageBag
    {
        // Turn this back into a string so it works w/ the Laravel validators
        $submissionValue = $submissionValue?->toAtomString();

        $rules = new RuleBag($fieldKey, ['date']);

        $disableWeekends = Arr::get($this->additional, 'datePicker.disableWeekends');
        $disableWeekdays = Arr::get($this->additional, 'datePicker.disableWeekdays');

        $rules->addIfNotNull('required', $this->validation('required'));
        $rules->addIf('nullable', ! $this->validation('required')); // so the rules for no weekends/weekdays don't fail

        if ($disableWeekdays) {
            $rules->add(new NotWeekday);
        }

        if ($disableWeekends) {
            $rules->add(new NotWeekend);
        }

        return $validator->make(
            [$fieldKey => $submissionValue],
            $rules->rules(),
            [],
            [$fieldKey => $fieldLabel]
        )->messages();
    }
}
