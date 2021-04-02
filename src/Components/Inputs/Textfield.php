<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\RuleBag;
use Northwestern\SysDev\DynamicForms\Rules\CheckWordCount;

class Textfield extends BaseComponent
{
    const TYPE = 'textfield';

    protected function processValidations(string $fieldKey, mixed $submissionValue, Factory $validator): MessageBag
    {
        $rules = new RuleBag($fieldKey, ['string']);
        $rules->addIf('required', $this->validation('required') === true);
        $rules->addIfNotNull(sprintf('min:%s', $this->validation('minLength')), $this->validation('minLength'));
        $rules->addIfNotNull(sprintf('max:%s', $this->validation('maxLength')), $this->validation('maxLength'));

        if ($this->validation('minWords')) {
            $rules->add(new CheckWordCount(CheckWordCount::MODE_MINIMUM, $this->validation('minWords')));
        }

        if ($this->validation('maxWords')) {
            $rules->add(new CheckWordCount(CheckWordCount::MODE_MAXIMUM, $this->validation('maxWords')));
        }

        // PHP needs the regexp armoured with slashes, so...
        $pattern = sprintf('/%s/', str_replace('/', '\/', $this->validation('pattern')));
        $rules->addIfNotNull(['regex', $pattern], $this->validation('pattern'));

        return $validator->make(
            [$fieldKey => $submissionValue],
            $rules->rules(),
        )->messages();
    }
}
