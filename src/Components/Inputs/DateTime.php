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
     * @param bool $userTimezone Set true if you want this in its original timezone.
     * @return CarbonInterface|null DateTime in UTC (unless userTimezone = true)
     */
    public function submissionValue(bool $userTimezone = false): ?CarbonInterface
    {
        if (! $this->submissionValue) {
            return null;
        }

        try {
            return (new Carbon($this->submissionValue))->utc();
        } catch (InvalidFormatException) {
            return null;
        }
    }

    public function processValidations(string $fieldKey, Factory $validator): MessageBag
    {
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
            // Note: we're using the raw submissionValue here; the method transforms this, which is undesirable!
            [$fieldKey => $this->submissionValue],
            $rules->rules(),
        )->messages();
    }
}
