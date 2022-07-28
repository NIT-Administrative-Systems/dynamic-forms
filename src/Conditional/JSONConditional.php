<?php

namespace Northwestern\SysDev\DynamicForms\Conditional;

use JWadhams\JsonLogic;
use Northwestern\SysDev\DynamicForms\JSONLogic\JsonLogicHelpers;

class JSONConditional implements ConditionalInterface
{
    public function __construct(array $jsonLogic)
    {
        $this->jsonLogic = JsonLogicHelpers::convertDataVars($jsonLogic);
    }

    public function __invoke(array $submissionValues): bool
    {
        return JsonLogic::apply($this->jsonLogic, $submissionValues);
    }
}
