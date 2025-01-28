<?php

namespace Northwestern\SysDev\DynamicForms\JSONLogic\LodashFunctions;

use Closure;
use stdClass;

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

        return false;
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

    /**
     * Gets the first element of an array. Passing n returns the first n elements.
     *
     * @usage __::first([1, 2, 3]);
     *        >> 1
     *
     * @param array    $array of values
     * @param int|null $take  number of values to return
     *
     * @return mixed
     *
     * @url https://github.com/me-io/php-lodash/blob/2.0.0/src/Traits/Collections.php#L48
     * @license MIT
     */
    public static function first(array $array, $take = null)
    {
        if (! $take) {
            return array_shift($array);
        }

        return array_splice($array, 0, $take);
    }

    /**
     * Check if value is an empty array or object. We consider any non enumerable as empty.
     *
     * @usage __::isEmpty([]);
     *        >> true
     *
     * @param mixed $value The value to check for emptiness.
     *
     * @return bool
     *
     * @url https://github.com/me-io/php-lodash/blob/2.0.0/src/Traits/Collections.php#L743
     * @license MIT
     */
    public static function isEmpty(mixed $value): bool
    {
        return (! is_array($value) && ! is_object($value)) || count((array) $value) === 0;
    }

    /**
     * Returns the minimum value from the collection. If passed an iterator, min will return min value returned by the
     * iterator.
     *
     * @usage __::min([1, 2, 3]);
     *        >> 1
     *
     * @param array $array array of values
     *
     * @return mixed
     *
     * @url https://github.com/me-io/php-lodash/blob/2.0.0/src/Traits/Collections.php#L170
     * @license MIT
     */
    public static function min(array $array = []): mixed
    {
        return min($array);
    }

    /**
     * Returns an array having only keys present in the given path list. Values for missing keys values will be filled
     * with provided default value.
     *
     * @usage __::pick(['a' => 1, 'b' => ['c' => 3, 'd' => 4]], ['a', 'b.d']);
     *        >> ['a' => 1, 'b' => ['d' => 4]]
     *
     * @param object|array $collection The collection to iterate over.
     * @param array        $paths      array paths to pick
     * @param null         $default
     *
     * @return array|object
     *
     * @url https://github.com/me-io/php-lodash/blob/2.0.0/src/Traits/Collections.php#L842
     * @license MIT
     */
    public static function pick(object|array $collection = [], array $paths = [], $default = null)
    {
        return self::reduce($paths, function ($results, $path) use ($collection, $default) {
            return self::set($results, $path, self::get($collection, $path, $default));
        }, self::isObjectStrict($collection) ? new stdClass() : []);
    }

    /**
     * Reduces $collection to a value which is the $accumulator result of running each
     * element in $collection thru $iterateFn, where each successive invocation is supplied
     * the return value of the previous.
     *
     * If $accumulator is not given, the first element of $collection is used as the
     * initial value.
     *
     * The $iterateFn is invoked with four arguments:
     * ($accumulator, $value, $index|$key, $collection).
     *
     * @usage __::reduce([1, 2], function ($sum, $number) {
     *                return $sum + $number;
     *            }, 0);
     *        >> 3
     *
     *        $a = [
     *            ['state' => 'IN', 'city' => 'Indianapolis', 'object' => 'School bus'],
     *            ['state' => 'IN', 'city' => 'Indianapolis', 'object' => 'Manhole'],
     *            ['state' => 'IN', 'city' => 'Plainfield', 'object' => 'Basketball'],
     *            ['state' => 'CA', 'city' => 'San Diego', 'object' => 'Light bulb'],
     *            ['state' => 'CA', 'city' => 'Mountain View', 'object' => 'Space pen'],
     *        ];
     *        $iterateFn = function ($accumulator, $value) {
     *            if (isset($accumulator[$value['city']]))
     *                $accumulator[$value['city']]++;
     *            else
     *                $accumulator[$value['city']] = 1;
     *            return $accumulator;
     *        };
     *        __::reduce($c, $iterateFn, []);
     *        >> [
     *            'Indianapolis' => 2,
     *            'Plainfield' => 1,
     *            'San Diego' => 1,
     *            'Mountain View' => 1,
     *         ]
     *
     *        $object = new \stdClass();
     *        $object->a = 1;
     *        $object->b = 2;
     *        $object->c = 1;
     *        __::reduce($object, function ($result, $value, $key) {
     *            if (!isset($result[$value]))
     *                $result[$value] = [];
     *            $result[$value][] = $key;
     *            return $result;
     *        }, [])
     *        >> [
     *             '1' => ['a', 'c'],
     *             '2' => ['b']
     *         ]
     *
     * @param array               $collection The collection to iterate over.
     * @param Closure             $iterateFn  The function invoked per iteration.
     * @param null|array          $accumulator
     *
     * @return array|mixed|null (*): Returns the accumulated value.
     *
     * @url https://github.com/me-io/php-lodash/blob/2.0.0/src/Traits/Collections.php#L908
     * @license MIT
     */
    public static function reduce($collection, Closure $iterateFn, null|array $accumulator = null)
    {
        if ($accumulator === null) {
            $accumulator = array_shift($collection);
        }

        self::doForEach(
            $collection,
            function ($value, $key, $collection) use (&$accumulator, $iterateFn) {
                $accumulator = $iterateFn($accumulator, $value, $key, $collection);
            }
        );

        return $accumulator;
    }

    /**
     * Iterate over elements of the collection and invokes iterate for each element.
     *
     * The iterate is invoked with three arguments: (value, index|key, collection).
     * Iterate functions may exit iteration early by explicitly returning false.
     *
     * @usage __::doForEach([1, 2, 3], function ($value) { print_r($value) });
     *        >> (Side effect: print 1, 2, 3)
     *
     * @param array|object $collection The collection to iterate over.
     * @param \Closure     $iterateFn  The function to call for each value
     *
     * @return bool
     *
     * @license MIT
     * @url https://github.com/me-io/php-lodash/blob/2.0.0/src/Traits/Collections.php#L369
     */
    public static function doForEach(array|object $collection, Closure $iterateFn)
    {
        foreach ($collection as $key => $value) {
            if ($iterateFn($value, $key, $collection) === false) {
                break;
            }
        }

        return true;
    }

    /**
     * Return a new collection with the item set at index to given value.
     * Index can be a path of nested indexes.
     *
     * If a portion of path doesn't exist, it's created. Arrays are created for missing
     * index in an array; objects are created for missing property in an object.
     *
     * @usage __::set(['foo' => ['bar' => 'ter']], 'foo.baz.ber', 'fer');
     *        >> '['foo' => ['bar' => 'ter', 'baz' => ['ber' => 'fer']]]'
     *
     * @param array|object|null $collection collection of values
     * @param string|int|null   $path       key or index
     * @param mixed             $value      the value to set at position $key
     *
     * @throws \Exception if the path consists of a non collection and strict is set to false
     *
     * @return array|object the new collection with the item set
     *
     * @license MIT
     * @url https://github.com/me-io/php-lodash/blob/2.0.0/src/Traits/Collections.php#L409
     */
    public static function set(array|object|null $collection, string|int|null $path, mixed $value = null)
    {
        if ($path === null) {
            return $collection;
        }
        $portions = self::split($path, '.', 2);
        $key = $portions[0];
        if (count($portions) === 1) {
            return self::universalSet($collection, $key, $value);
        }
        // Here we manage the case where the portion of the path points to nothing,
        // or to a value that does not match the type of the source collection
        // (e.g. the path portion 'foo.bar' points to an integer value, while we
        // want to set a string at 'foo.bar.fun'. We first set an object or array
        //  - following the current collection type - to 'for.bar' before setting
        // 'foo.bar.fun' to the specified value).
        if (! self::has($collection, $key)
            || (self::isObjectStrict($collection) && ! self::isObjectStrict(self::get($collection, $key)))
            || (self::isArray($collection) && ! self::isArray(self::get($collection, $key)))
        ) {
            $collection = self::universalSet($collection, $key, self::isObjectStrict($collection) ? new stdClass : []);
        }

        return self::universalSet($collection, $key, self::set(self::get($collection, $key), $portions[1], $value));
    }

    /**
     * Return true if $collection contains the requested $key.
     *
     * In constraint to isset(), __::has() returns true if the key exists but is null.
     *
     * @usage __::has(['foo' => ['bar' => 'num'], 'foz' => 'baz'], 'foo.bar');
     *        >> true
     *
     *        __::hasKeys((object) ['foo' => 'bar', 'foz' => 'baz'], 'bar');
     *        >> false
     *
     * @param null|array|object $collection of key values pairs
     * @param string            $path       Path to look for.
     *
     * @return bool
     */
    public static function has(null|array|object $collection, string|array $path): bool
    {
        if (is_array($path)) {
            $path = implode('.', $path);
        }

        $portions = self::split($path, '.', 2);
        $key = $portions[0];

        if (count($portions) === 1) {
            return array_key_exists($key, (array) $collection);
        }

        return self::has(self::get($collection, $key), $portions[1]);
    }

    /**
     * @param mixed $collection
     * @param mixed $key
     * @param mixed $value
     *
     * @return mixed
     *
     * @license MIT
     * @url https://github.com/me-io/php-lodash/blob/2.0.0/src/Traits/Collections.php#L442
     */
    public static function universalSet(mixed $collection, mixed $key, mixed $value): mixed
    {
        $set_object = function ($object, $key, $value) {
            $newObject = clone $object;
            $newObject->$key = $value;

            return $newObject;
        };
        $set_array = function ($array, $key, $value) {
            $array[$key] = $value;

            return $array;
        };
        $setter = self::isObjectStrict($collection) ? $set_object : $set_array;

        return call_user_func_array($setter, [$collection, $key, $value]);
    }

    /**
     * Get item of an array by index, accepting nested index.
     *
     * @usage __::get(['foo' => ['bar' => 'ter']], 'foo.bar');
     *        >> 'ter'
     *
     * @param array|object $collection array of values
     * @param null|string  $key        key or index
     * @param mixed        $default    default value to return if index not exist
     *
     * @return mixed
     *
     * @license MIT
     * @url https://github.com/me-io/php-lodash/blob/2.0.0/src/Traits/Collections.php#L69
     */
    public static function get(array|object $collection = [], null|string $key = null, mixed $default = null): mixed
    {
        if (self::isNull($key)) {
            return $collection;
        }

        if (! self::isObjectStrict($collection) && isset($collection[$key])) {
            return $collection[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (self::isObjectStrict($collection)) {
                if (! isset($collection->{$segment})) {
                    return $default instanceof Closure ? $default() : $default;
                } else {
                    $collection = $collection->{$segment};
                }
            } else {
                if (! isset($collection[$segment])) {
                    return $default instanceof Closure ? $default() : $default;
                } else {
                    $collection = $collection[$segment];
                }
            }
        }

        return $collection;
    }
}
