<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\RuleBag;

class Email extends Textfield
{
    const TYPE = 'email';

    protected function processValidations(string $fieldKey, string $fieldLabel, mixed $submissionValue, Factory $validator): MessageBag
    {
        $rules = new RuleBag($fieldKey, ['string']);

        // This has all the same stuff as a textfield (including, weirdly, word length),
        // so process it through there first.
        $bag = parent::processValidations($fieldKey, $fieldLabel, $submissionValue, $validator);

        // And then email just adds a requirement that the string be a valid-looking email address,
        // assuming the data is present. The nullable rule should make email pass if it's
        // empty -- we've already got required handled in the parent!
        $rules->add('nullable');
        $rules->add('email');

        $valid = $validator->make(
            [$fieldKey => $submissionValue],
            $rules->rules(),
            [],
            [$fieldKey => $fieldLabel]
        );

        return $bag->merge($valid->messages());
    }
}
