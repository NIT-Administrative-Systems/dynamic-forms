<?php

namespace Northwestern\SysDev\DynamicForms\Conditional;

use Illuminate\Support\Arr;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Select;
use Northwestern\SysDev\DynamicForms\Components\Inputs\SelectBoxes;

class SimpleConditional implements ConditionalInterface
{
    public function __construct(
        protected bool $show,
        protected string $when,
        protected string $equalTo
    ) {
        //
    }

    public function __invoke(array $submissionValues): bool
    {
        $value = Arr::get($submissionValues, $this->when);

        // Handle all regular cases
        if ($value === $this->equalTo) {
            return $this->show;
        }

        // Handle submissionValues with other formats
        if (is_array($value)) {
            /** Handles @see SelectBoxes */
            if (isset($value[$this->equalTo]) && $value[$this->equalTo]) {
                return $this->show;
            }

            /** Handles @see Select */
            if (! isset($value[$this->equalTo]) && in_array($this->equalTo, $value)) {
                return $this->show;
            }
        }

        return ! $this->show;
    }
}
