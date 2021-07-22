<?php


namespace Northwestern\SysDev\DynamicForms\Conditional\LodashFunctions;


trait Util
{
    public static function constant($value)
    {
        return function () use ($value) {return $value;};
    }
    public static function iteratee($value = null)
    {
        return \_\internal\baseIteratee($value);
    }
    public static function matches($source)
    {
        return function ($object) use ($source) {
            foreach($source as $key => $value)
            {
                if(!(key_exists($key, $object) && $object[$key] == $value))
                {
                    return false;
                }
            }
            return true;
        };
    }
    public static function matchesProperty($path, $srcValue)
    {
        return function ($object) use ($srcValue, $path) {
            if(key_exists($path, $object) && $object[$path] == $srcValue)
            {
                return true;
            }
            return false;
        };
    }
    public static function range($start = null, $stop = null, int $step = 1)
    {
        if ($stop == null && $start != null)
        {
            $stop = $start;
            $start = 0;
        }
        if($step == 0)
        {
            return array_pad([], $stop - $start, $start);
        }
        $ret = range($start, $stop, $step);
        if(($stop - $start) % $step == 0 )
        {
            array_pop($ret);
        }
        return $ret;
    }
    public static function rangeRight($start = null, $stop = null, int $step = 1)
    {
        return array_reverse(self::range($start,$stop,$step));
    }
    public static function stubArray()
    {
        return [];
    }
    public static function stubFalse()
    {
        return false;
    }
    public static function stubObject()
    {
        //must be array because stdClass is not used, it is all assoc arrays
        return [];
    }
    public static function stubString()
    {
        return '';
    }
    public static function stubTrue()
    {
        return true;
    }
    public static function toPath($value)
    {
        $value = str_replace('[', '.', $value);
        $value = str_replace(']', '', $value);
        return explode('.',  $value);
    }
    public static function times($n, $iteratee)
    {
        $ret = [];
        for($i = 0; $i < $n;  $i++)
        {
            $ret[] = $iteratee();
        }
        return $ret;
    }

}
