<?php

namespace Northwestern\SysDev\DynamicForms\Rules;

use Illuminate\Contracts\Validation\Rule;

class TimeFormat implements Rule
{
    /**
     * @see https://www.php.net/manual/en/datetime.createfromformat.php
     *
     * @param string $timeFormat Any valid time values for a \DateTime::createFromFormat format
     */
    public function __construct(
        protected string $timeFormat = 'H:i:s',
    ) {
        //
    }

    /**
     * Validates a time by building a DateTime instance.
     *
     * This has some non-obvious stuff going on: mainly, the comparison of the original value
     * a formatted DateTime. You might think that DateTime::createFromFormat would return false
     * if an invalid date is given -- and yes, if sufficiently invalid data is passed in, it will.
     *
     * But, it will "helpfully" try to resolve invalid-but-close strings. To wit:
     *
     *     >>> \DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-00 00:00:00');
     *     => DateTime @1577750400 {#4493
     *          date: 2019-12-31 00:00:00.0 UTC (+00:00),
     *        }
     *
     * So comparing the formatted value to the original value ensures that DateTime both parsed it, *and*
     * didn't do anything ridiculous with the data.
     */
    public function passes($attribute, $value)
    {
        // A date which does NOT have leap seconds
        $value = sprintf('2021-03-25 %s', $value);
        $format = sprintf('Y-m-d %s', $this->timeFormat);

        $datetime = \DateTime::createFromFormat($format, $value);

        return $datetime
            ? $datetime->format($format) === $value
            : false;
    }

    public function message()
    {
        return ':attribute must be a valid time.';
    }
}
