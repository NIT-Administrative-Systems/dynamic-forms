<?php

namespace Northwestern\SysDev\DynamicForms\Conditional\LodashFunctions;

trait Lang
{
    public static function castArray(mixed $value = null): array
    {
        if (func_num_args() == 0) {
            return [];
        }
        if (is_array($value)) {
            if ($value !== array_values($value)) {
                return [$value];
            }

            return $value;
        }

        return [$value];
    }

    public static function gt(mixed $value, mixed $other): bool
    {
        return $value > $other;
    }

    public static function gte(mixed $value, mixed $other): bool
    {
        return $value >= $other;
    }

    public static function lt(mixed $value, mixed $other): bool
    {
        return $value < $other;
    }

    public static function lte(mixed $value, mixed $other): bool
    {
        return $value <= $other;
    }

    public static function isArrayLike(mixed $value): bool
    {
        return is_array($value) || is_string($value);
    }

    public static function isArrayLikeObject(mixed $value): bool
    {
        return is_array($value);
    }

    public static function isBoolean(mixed $value): bool
    {
        return is_bool($value);
    }

    public static function isFinite(mixed $value): bool
    {
        return is_float($value) || is_int($value);
    }

    public static function isInteger(mixed $value): bool
    {
        return is_int($value);
    }

    public static function isLength(mixed $value): bool
    {
        return is_int($value);
    }

    public static function isMatch(array $object, array $source): bool
    {
        foreach ($source as $key => $value) {
            if (! (array_key_exists($key, $object) && $object[$key] == $value)) {
                return false;
            }
        }

        return true;
    }

    public static function isNan(mixed $value): bool
    {
        return is_float($value) && is_nan($value);
    }

    public static function isNumber(mixed $value): bool
    {
        return is_int($value) || is_float($value);
    }

    public static function isObject(mixed $value): bool
    {
        return is_object($value) || is_array($value);
    }

    public static function toArray(mixed $value): array
    {
        if (self::isObject($value)) { //even if it is a js object it will be passed as an associative array
            $ret = [];
            foreach ($value as $key => $item) {
                $ret[] = $item;
            }

            return $ret;
        }
        if (is_string($value)) {
            return str_split($value);
        }

        return [];
    }

    public static function toFinite(mixed $value): float
    {
        return floatval($value);
    }

    public static function toInteger(mixed $value): int
    {
        return intval($value);
    }

    public static function toLength(mixed $value): int
    {
        return intval($value) < 0 ? 0 : intval($value);
    }

    public static function toNumber(mixed $value): float
    {
        return floatval($value);
    }

    public static function toSafeInteger(mixed $value): bool
    {
        return intval($value);
    }

    public static function toString(mixed $value): string
    {
        if (is_array($value)) {
            $ret = '';
            foreach ($value as $subpart) {
                $ret .= strval($subpart).',';
            }
            $temp = rtrim($ret, ',');

            return $temp;
        }

        return strval($value);
    }
}
