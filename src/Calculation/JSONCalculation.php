<?php

namespace Northwestern\SysDev\DynamicForms\Calculation;

use JWadhams\JsonLogic;
use Northwestern\SysDev\DynamicForms\JSONLogic\JsonLogicHelpers;

class JSONCalculation implements CalculationInterface
{
    protected array $jsonLogic;

    public function __construct(array $jsonLogic)
    {
        $this->jsonLogic = JsonLogicHelpers::convertDataVars($jsonLogic);
    }

    public function __invoke(array $submissionValues): mixed
    {
        return JsonLogic::apply($this->jsonLogic, $submissionValues);
    }
}
