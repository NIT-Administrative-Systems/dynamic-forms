<?php


namespace Northwestern\SysDev\DynamicForms\Conditional\LodashFunctions;


trait Arrays
{
    public static function fromPairs(array $pairs) : array
    {
        //has to be array not StdClass
        $result = [];

        if (count($pairs) == 0)
        {
            return $result;
        }

        foreach ($pairs as $pair)
        {
            $result[$pair[0]] = $pair[1];
        }

        return $result;
    }
    public static function join(array $array, string $separator=','): string
    {
        return implode($separator, $array);
    }
    public static function slice(array $array, int $start = 0, int $end = null): array
    {
        if($end == null)
        {
            return array_slice($array, $start );
        }
        return array_slice($array, $start, $end - $start);
    }
    public static function sortedIndex(array $array, mixed $value) : int
    {
        $size = count($array);
        if($size == 0 )
        {
            return 0;
        }
        if($value > $array[$size - 1])
        {
            return $size;
        }
        for($i = 0; $i < $size; $i++)
        {
            if($array[$i] >= $value)
            {
                return $i;
            }
        }
    }
    public static function sortedIndexOf(array $array, mixed $value): int
    {
        return \_\indexOf($array, $value);
    }
    public static function sortedLastIndex(array $array, mixed $value) : int
    {
        $size = count($array);
        if($size == 0 )
        {
            return 0;
        }
        if($value < $array[0])
        {
            return 0;
        }
        for($i = $size - 1; $i >= 0; $i--)
        {
            if($value >= $array[$i])
            {
                return $i + 1;
            }
        }
    }
    public static function sortedLastIndexOf(array $array, mixed $value): int
    {
        return \_\lastIndexOf($array, $value);
    }
    public static function sortedUniq(array $array) : array
    {
        return array_values(array_unique($array));
    }
}
