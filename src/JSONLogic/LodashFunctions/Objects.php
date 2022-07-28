<?php

namespace Northwestern\SysDev\DynamicForms\JSONLogic\LodashFunctions;

trait Objects
{
    public static function at(array $object, string | array $paths): array
    {
        $ret = [];
        foreach ($paths as $path) {
            $ret[] = \_\get($object, $path);
        }

        return $ret;
    }

    public static function entries(array $object): array
    {
        $ret = [];
        foreach ($object as $key => $item) {
            $ret[] = [$key, $item];
        }

        return $ret;
    }

    public static function entriesIn(array $object): array
    {
        //This is the same as entries since we do not support inherited properties for objects
        return self::entries($object);
    }

    //This is an alias of entries
    public static function toPairs(array $object): array
    {
        return self::entries($object);
    }

    //This is an alias of entriesIn
    public static function toPairsIn(array $object): array
    {
        return self::entries($object);
    }

    public static function has(array $object, string | array $path): bool
    {
        if (is_array($path)) {
            $path = implode('.', $path);
        }

        return \__\Traits\Collections::has($object, $path);
    }

    public static function hasIn(array $object, string | array $path): bool
    {
        //This is the same as has since we do not support inherited properties for objects
        return self::has($object, $path);
    }

    public static function invert(array $object): array
    {
        return array_flip($object);
    }

    public static function keys(array | string $object): array
    {
        if (self::isObject($object)) { //even if it is a js object it will be passed as an associative array
            $ret = [];
            foreach ($object as $key => $item) {
                $ret[] = $key;
            }

            return $ret;
        }
        if (is_string($object)) {
            return array_map('strval', range(0, strlen($object) - 1));
        }

        return [];
    }

    public static function keysIn(array | string $object): array
    {
        //This is the same as keys since we do not support inherited properties for objects
        return self::keys($object);
    }

    public static function omit(array $object, array $paths): array
    {
        $picked = \__\Traits\Collections::pick($object, $paths);
        $ret = [];
        foreach ($object as $key => &$item) {
            if (! array_key_exists($key, $picked)) {
                $ret[$key] = $item;
            }
        }

        return $ret;
    }

    public static function result(array $object, array | string $path, mixed $defaultValue = null): mixed
    {
        //This is the same as get since we do not support functions within objects
        return \_\get($object, $path, $defaultValue);
    }

    public static function values(array | string $object): array
    {
        if (self::isObject($object)) {
            $ret = [];
            foreach ($object as $key => $item) {
                $ret[] = $item;
            }

            return $ret;
        }
        if (is_string($object)) {
            return str_split($object);
        }

        return [];
    }

    public static function valuesIn(array | string $object): array
    {
        //This is the same as values since we do not support inherited properties for objects
        return self::values($object);
    }
}
