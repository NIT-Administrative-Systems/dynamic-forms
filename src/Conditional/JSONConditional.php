<?php

namespace Northwestern\SysDev\DynamicForms\Conditional;

use JWadhams\JsonLogic;

class JSONConditional implements ConditionalInterface
{
    public function __construct(
        protected array $jsonLogic,
    ) {
        //Replace variable names to be valid in JSON Logic
        // (may have to do similar for rows if we add support for datagrids)
        array_walk_recursive(
            $jsonLogic,
            function (&$value, $key) {
                if ($key == 'var' && str_starts_with($value, 'data.')) {
                    $value = substr($value, 5);
                }
            }
        );
        $this->jsonLogic = $jsonLogic;
    }

    public function __invoke(array $submissionValues): bool
    {
        return JsonLogic::apply($this->jsonLogic, $submissionValues);
    }
}
