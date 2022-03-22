<?php

namespace Northwestern\SysDev\DynamicForms\JSONLogic;

abstract class JsonLogicHelpers
{
    /**
     * Replace the data.{something} variable names that Formiojs uses with just {something}.
     *
     * The JSONLogic library (and the way we feed it the form data) require us to drop the `data.` prefix.
     */
    public static function convertDataVars(array $jsonLogic): array
    {
        // May have to do similar for rows if we add support for datagrids.
        array_walk_recursive(
            $jsonLogic,
            function (&$value, $key) {
                if ($key == 'var' && str_starts_with($value, 'data.')) {
                    $value = substr($value, 5);
                }
            }
        );

        return $jsonLogic;
    }
}
