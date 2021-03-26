<?php

namespace Northwestern\SysDev\DynamicForms\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class NotWeekend implements Rule
{
    public function passes($attribute, $value)
    {
        $value = new Carbon($value);

        return ! $value->isWeekend();
    }

    public function message()
    {
        return ':attribute must not be a weekend.';
    }
}
