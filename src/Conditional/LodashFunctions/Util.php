<?php


namespace Northwestern\SysDev\DynamicForms\Conditional\LodashFunctions;


use Closure;

trait Util
{
    public static function constant(mixed $value) : Closure
    {
        return function () use ($value) {return $value;};
    }
    public static function iteratee(mixed $value = null) : callable
    {
        return \_\internal\baseIteratee($value);
    }
    public static function matches(array $source): Closure
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
    public static function matchesProperty(array|string $path, mixed $srcValue): Closure
    {
        return function ($object) use ($srcValue, $path) {
            if(key_exists($path, $object) && $object[$path] == $srcValue)
            {
                return true;
            }
            return false;
        };
    }
    public static function range(int $start = null, int $stop = null, int $step = 1) : array
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
    public static function rangeRight(int $start = null, int $stop = null, int $step = 1) : array
    {
        return array_reverse(self::range($start,$stop,$step));
    }
    public static function stubArray() : array
    {
        return [];
    }
    public static function stubFalse() : bool
    {
        return false;
    }
    public static function stubObject() : array
    {
        //must be array because stdClass is not used, it is all assoc arrays
        return [];
    }
    public static function stubString() : string
    {
        return '';
    }
    public static function stubTrue() : bool
    {
        return true;
    }
    public static function toPath(string $value) : array
    {
        $value = str_replace('[', '.', $value);
        $value = str_replace(']', '', $value);
        return explode('.',  $value);
    }
    public static function times(int $n, callable $iteratee) : array
    {
        $ret = [];
        for($i = 0; $i < $n;  $i++)
        {
            $ret[] = $iteratee();
        }
        return $ret;
    }

}
