<?php

namespace Northwestern\SysDev\DynamicForms\JSONLogic;

use JWadhams\JsonLogic;
use Northwestern\SysDev\DynamicForms\Validation\JSONValidation;

/**
 * "Static analysis" for JSONLogic "if" expressions.
 *
 * Useful for {@see JSONValidation::checkDefinition()} to determine whether a validation rule is capable of returning
 * a `true` value.
 *
 * @see https://jsonlogic.com/operations.html JsonLogic Operations Docs
 */
abstract class JsonLogicIfOperationAnalysis
{
    /**
     * Operations that return one of their arguments, so they can return true if any argument can.
     */
    private const array PASSTHROUGH_OPERATIONS = ['and', 'or', 'log'];

    /**
     * Operations that only return strings, numbers, or arrays. Never `true`.
     */
    private const NEVER_TRUE_OPERATIONS = [
        'cat',
        'substr',
        '+',
        '-',
        '*',
        '/',
        '%',
        'min',
        'max',
        'merge',
        'map',
        'filter',
        'missing',
        'missing_some',
    ];

    /**
     * Determine whether an expression *could* evaluate to `true`.
     *
     * There is some amount of uncertainty since we do not know the values of variables, so this will err on the side
     * of caution and return true if it cannot definitively prove the JSONLogic expression would never return `true`.
     *
     * This is static analysis and NOT evaluation, so all the expression's *possible* outputs are considered.
     * This never says false when true is possible, but it will say true for a true behind a condition that can
     * never pass, like `{"if": [false, true, "Error"]}`.
     *
     * Operations (custom functions & Lodash) that are not known to Dynamic Forms are treated as possibly returning
     * `true`.
     */
    public static function mayReturnTrue(mixed $expression): bool
    {
        if ($expression === true) {
            return true;
        }

        // Scalars, plus arrays and objects that are data rather than logic (JsonLogic::apply() maps over those,
        // so the result is an array, never true).
        if (! JsonLogic::is_logic($expression)) {
            return false;
        }

        $operation = JsonLogic::get_operator($expression);
        $arguments = JsonLogic::get_values($expression);

        if ($operation === 'if' || $operation === '?:') {
            return self::anyMayReturnTrue(self::ifOutputs($arguments));
        }

        if (in_array($operation, self::PASSTHROUGH_OPERATIONS, true)) {
            return self::anyMayReturnTrue($arguments);
        }

        if (in_array($operation, self::NEVER_TRUE_OPERATIONS, true)) {
            return false;
        }

        // Comparisons & logic (==, <, !, in, some, etc.), var, reduce, and unknown operations.
        //
        // "var" is possibly true because submission values can be true, e.g. a checkbox. Rules like
        // {"if": [cond, {"var": "data.agreed"}, "Error"]} are legitimate and should not be flagged.
        return true;
    }

    /**
     * The output arguments of an "if", which may be chained: [condition, output, condition, output, ..., else].
     *
     * @return list<mixed>
     */
    private static function ifOutputs(array $arguments): array
    {
        $arguments = array_values($arguments);
        $outputs = [];

        foreach ($arguments as $index => $argument) {
            $isOutput = $index % 2 === 1;
            $isElse = $index === count($arguments) - 1;

            if ($isOutput || $isElse) {
                $outputs[] = $argument;
            }
        }

        return $outputs;
    }

    private static function anyMayReturnTrue(array $expressions): bool
    {
        foreach ($expressions as $expression) {
            if (self::mayReturnTrue($expression)) {
                return true;
            }
        }

        return false;
    }
}
