<?php


namespace Northwestern\SysDev\DynamicForms\Conditional\LodashFunctions;


trait Objects
{
    public static function at($object, $paths)
    {
        $ret = [];
        foreach ($paths as $path)
        {
            $ret[] =  \_\get($object, $path);
        }
        return $ret;
    }
    public static function entries($object)
    {
        $ret = [];
        foreach($object as $key => $item)
        {
            $ret[] = [$key, $item];
        }
        return $ret;
    }
    public static function entriesIn($object)
    {
        //This is the same as entries since we do not support inherited properties for objects
        return self::entries($object);
    }
    //This is an alias of entries
    public static function toPairs($object)
    {
        return self::entries($object);
    }
    //This is an alias of entriesIn
    public static function toPairsIn($object)
    {
        return self::entries($object);
    }
    public static function has($object, $path)
    {
        if(is_array($path))
        {
            $path = implode('.', $path);
        }
        return \__\Traits\Collections::has($object, $path);
    }
    public static function hasIn($object, $path)
    {
        //This is the same as has since we do not support inherited properties for objects
        return self::has($object, $path);
    }
    public static function invert($object)
    {
        return array_flip($object);
    }
    public static function keys($object)
    {
        if(self::isObject($object)) //even if it is a js object it will be passed as an associative array
        {
            $ret = [];
            foreach($object as $key => $item)
            {
                $ret[] = $key;
            }
            return $ret;
        }
        if(is_string($object))
        {
            return array_map('strval', range(0, strlen($object) - 1) );
        }
        return [];
    }
    public static function keysIn($object)
    {
        //This is the same as keys since we do not support inherited properties for objects
        return self::keys($object);
    }
    public static function omit($object, array $paths)
    {
        $picked = \_\pick((object) $object, $paths);
        $ret = [];
        foreach($object as $key => &$item)
        {
            if(!property_exists($picked, $key))
            {
                $ret[$key] = $item;
            }
        }
        return $ret;
    }
    public static function result($object, $path, $defaultValue = null)
    {
        //This is the same as get since we do not support functions within objects
        return \_\get($object, $path, $defaultValue);
    }
    public static function values($object)
    {
        if(self::isObject($object))
        {
            $ret = [];
            foreach($object as $key => $item)
            {
                $ret[] = $item;
            }
            return $ret;
        }
        if(is_string($object))
        {
            return str_split($object);
        }
        return [];
    }
    public static function valuesIn($object)
    {
        //This is the same as values since we do not support inherited properties for objects
        return self::values($object);
    }
}
