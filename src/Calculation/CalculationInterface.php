<?php

namespace Northwestern\SysDev\DynamicForms\Calculation;

interface CalculationInterface
{
    public function __invoke(array $submissionValues): mixed;
}
