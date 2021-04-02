<?php

namespace Northwestern\SysDev\DynamicForms\Components\Inputs;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Validation\Factory;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\RuleBag;

class Signature extends BaseComponent
{
    const TYPE = 'signature';

    /**
     * Runs validations.
     *
     * Note that signature validation is pretty iffy. The signature box is captured as a PNG,
     * so as soon as a user clicks into it and it makes a snapshot (even w/out drawing), we get
     * an image.
     *
     * That's a limitation of the component in formiojs. If we wanted to fix it, we'd need some
     * machine vision. Which is not impossible! But unless/until that becomes a thing people want,
     * not gunna go on that particular vision quest (pun intended).
     * @param string $fieldKey
     * @param mixed $submissionValue
     * @param Factory $validator
     * @return MessageBag
     */
    protected function processValidations(string $fieldKey, mixed $submissionValue, Factory $validator): MessageBag
    {
        $rules = new RuleBag($fieldKey, ['string']);

        $required = $this->validation('required');

        if ($required) {
            $rules->add('required');
        } else {
            $rules->add('nullable');
        }

        $rules->add(['startsWith', 'data:image/png;base64,']);

        return $validator->make(
            [$fieldKey => $submissionValue],
            $rules->rules(),
        )->messages();
    }
}
