<?php


namespace Northwestern\SysDev\DynamicForms\Conditional\LodashFunctions;

trait Lang
{
    public static function castArray($value = null)
    {
        if (func_num_args() == 0)
        {
            return [];
        }
        if(is_array($value))
        {
            if($value !== array_values($value))
            {
                return [$value];
            }
            return $value;
        }
        return [$value];
    }
    public static function gt($value, $other): bool
    {
        return $value > $other;
    }
    public static function gte($value, $other): bool
    {
        return $value >= $other;
    }
    public static function lt($value, $other): bool
    {
        return $value < $other;
    }
    public static function lte($value, $other): bool
    {
        return $value <= $other;
    }
    public static function isArrayLike($value)
    {
        return is_array($value) || is_string($value);
    }
    public static function isArrayLikeObject($value)
    {
        return is_array($value);
    }
    public static function isBoolean($value)
    {
        return is_bool($value);
    }
    public static function isFinite($value)
    {
        return is_float($value) || is_int($value);
    }
    public static function isInteger($value)
    {
        return is_int($value);
    }
    public static function isLength($value)
    {
        return is_int($value);
    }
    public static function isMatch($object, $source)
    {
        foreach($source as $key => $value)
        {
            if(!(key_exists($key, $object) && $object[$key] == $value))
            {
                return false;
            }
        }
        return true;
    }
    public static function isNan($value)
    {
        return is_float($value) && is_nan($value);
    }
    public static function isNumber($value): bool
    {
        return is_int($value) || is_float($value);
    }
    public static function isObject($value): bool
    {
        return is_object($value) || is_array($value);
    }
    public static function toArray($value) : array
    {
        if(self::isObject($value) ) //even if it is a js object it will be passed as an associative array
        {
            $ret = [];
            foreach($value as $key => $item)
            {
                $ret[] = $item;
            }
            return $ret;
        }
        if(is_string($value))
        {
            return str_split($value);
        }
        return [];
    }
    public static function toFinite($value)
    {
        return floatval($value);
    }
    public static function toInteger($value)
    {
        return intval($value);
    }
    public static function toLength($value)
    {
        return intval($value) < 0 ? 0 : intval($value);
    }
    public static function toNumber($value)
    {
        return floatval($value);
    }
    public static function toSafeInteger($value)
    {
        return intval($value);
    }
    public static function toString($value)
    {
        if(is_array($value))
        {
            $ret = '';
            foreach ($value as $subpart)
            {
                $ret .= strval($subpart).',';
            }
            return rtrim($ret, ',');
        }
        return strval($value);
    }
}
