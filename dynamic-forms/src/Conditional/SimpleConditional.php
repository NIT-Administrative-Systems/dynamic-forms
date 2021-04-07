<?php

namespace Northwestern\SysDev\DynamicForms\Conditional;

use Illuminate\Support\Arr;

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

        return ($value === $this->equalTo)
            ? $this->show
            : ! $this->show;
    }
}
