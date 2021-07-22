<?php


namespace Northwestern\SysDev\DynamicForms\Conditional\LodashFunctions;


trait Math
{
    public static function ceil($number, $precision = 0)
    {
        $mult = pow(10, $precision);
        //the double casting helps keep precision bugs in check, the 4th unit test wouldnt pass without it
        //downside is it does limit prection of numbers to 14 sig figs
        return ceil(floatval(strval($number * $mult))) / $mult;
    }
    public static function divide($dividend, $divisor)
    {
        return $dividend / $divisor;
    }
    public static function floor($number, $precision = 0)
    {
        $mult = pow(10, $precision);
        //the double casting helps keep precision bugs in check, the 4th unit test wouldnt pass without it
        //downside is it does limit prection of numbers to 14 sig figs
        return floor(floatval(strval($number * $mult))) / $mult;
    }
    public static function mean($array)
    {
        //lodash return nan on empty array
        return count($array) != 0 ? array_sum($array) / count($array) : NAN;
    }
    public static function multiply($multiplier, $multiplicand)
    {
        return $multiplier * $multiplicand;
    }
    public static function round($number, $precision = 0)
    {
        return round($number, $precision);
    }
    public static function subtract($minuend, $subtrahend)
    {
        return $minuend - $subtrahend;
    }
    public static function sum($array)
    {
        return array_sum($array);
    }
}
