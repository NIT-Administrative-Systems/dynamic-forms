<?php

namespace Northwestern\SysDev\DynamicForms\Conditional\LodashFunctions;

trait Math
{
    public static function ceil(int | float $number, int $precision = 0): int | float
    {
        $mult = pow(10, $precision);
        //the double casting helps keep precision bugs in check, the 4th unit test wouldnt pass without it
        //downside is it does limit prection of numbers to 14 sig figs
        return ceil(floatval(strval($number * $mult))) / $mult;
    }

    public static function divide(int | float $dividend, int | float $divisor): int | float
    {
        return $dividend / $divisor;
    }

    public static function floor(int | float $number, int $precision = 0): int | float
    {
        $mult = pow(10, $precision);
        //the double casting helps keep precision bugs in check, the 4th unit test wouldnt pass without it
        //downside is it does limit prection of numbers to 14 sig figs
        return floor(floatval(strval($number * $mult))) / $mult;
    }

    public static function mean(array $array): int | float
    {
        //lodash return nan on empty array
        return count($array) != 0 ? array_sum($array) / count($array) : NAN;
    }

    public static function multiply(int | float $multiplier, int | float $multiplicand): int | float
    {
        return $multiplier * $multiplicand;
    }

    public static function round(int | float $number, int $precision = 0): float
    {
        return round($number, $precision);
    }

    public static function subtract(int | float $minuend, int | float $subtrahend): int | float
    {
        return $minuend - $subtrahend;
    }

    public static function sum($array): int | float
    {
        return array_sum($array);
    }
}
