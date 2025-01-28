<?php

declare(strict_types=1);

namespace Northwestern\SysDev\DynamicForms\JSONLogic\LodashFunctions;

trait Strings
{
    public static function split(string $input, string $delimiter, int $limit = PHP_INT_MAX): array
    {
        return explode($delimiter, $input, $limit);
    }
}
