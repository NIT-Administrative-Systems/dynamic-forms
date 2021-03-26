<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\RuleBag;

class Url extends Textfield
{
    const TYPE = 'url';

    protected function processValidations(string $fieldKey, Factory $validator): MessageBag
    {
        $fieldKey = $this->label() ?? $this->key();
        $rules = new RuleBag($fieldKey, ['string']);

        // This has all the same stuff as a textfield (including, weirdly, word length),
        // so process it through there first.
        $bag = parent::processValidations($fieldKey, $validator);

        // And then URL just adds a requirement that the string be a valid-looking URL,
        // assuming the data is present. The nullable rule should make URL pass if it's
        // empty -- we've already got required handled in the parent!
        $rules->add('nullable');
        $rules->add('url');

        $validator = app()->make('validator')->make(
            [$fieldKey => $this->submissionValue()],
            $rules->rules(),
        );

        return $bag->merge($validator->messages());
    }
}
