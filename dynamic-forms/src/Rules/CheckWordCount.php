<?php

namespace Northwestern\SysDev\DynamicForms\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckWordCount implements Rule
{
    const MODE_MINIMUM = 'MIN';
    const MODE_MAXIMUM = 'MAX';

    public function __construct(
        protected string $mode,
        protected int $size
    ) {
        $valid_modes = [self::MODE_MINIMUM, self::MODE_MAXIMUM];
        if (! in_array($this->mode, $valid_modes)) {
            throw new \TypeError('Invalid mode');
        }
    }

    public function passes($attribute, $value)
    {
        $word_count = count(preg_split('/\s+/', $value));

        return $this->mode === self::MODE_MINIMUM
            ? $word_count >= $this->size
            : $word_count <= $this->size;
    }

    public function message()
    {
        $mode_phrase = $this->mode === self::MODE_MINIMUM ? 'at least' : 'no more than';

        return sprintf(
            ':attribute must have %s %s words.',
            $mode_phrase,
            $this->size
        );
    }
}
