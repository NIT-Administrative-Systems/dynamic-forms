<?php

namespace Northwestern\SysDev\DynamicForms\JSONLogic\LodashFunctions;

trait Collection
{
    public static function includes(string | object | array $collection, mixed $value, int $fromIndex = 0): bool
    {
        if (is_string($collection)) {
            return str_contains(substr($collection, $fromIndex), $value);
        }
        if (is_object($collection)) {
            foreach ($collection as $key => $item) {
                if ($value === $item) {
                    return true;
                }
            }

            return false;
        }
        if (is_array($collection)) {
            return in_array($value, array_slice($collection, $fromIndex));
        }
    }

    public static function orderBy(?iterable $collection, array $iteratee, array $orders): array
    {
        $temp = \_\orderBy($collection, $iteratee, $orders);
        $ret = [];
        foreach ($temp as $temp2) {
            $ret[] = $temp2['value'];
        }

        return $ret;
    }
}
