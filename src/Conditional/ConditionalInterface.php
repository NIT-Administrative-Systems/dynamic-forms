<?php

namespace Northwestern\SysDev\DynamicForms\Conditional;

interface ConditionalInterface
{
    public function __invoke(array $submissionValues): bool;
}
