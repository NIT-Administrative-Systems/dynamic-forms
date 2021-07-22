<?php


namespace Northwestern\SysDev\DynamicForms;


use JWadhams\JsonLogic;

class JSONLogicInitHelper
{

    public function __construct()
    {
        $this->registerLodash();
    }


    /**
     * Registers all of the lodash functions.
     */
    private function registerLodash(): void
    {

        //This list contains all the lodash functions (supported by formsio) and implementation source
        // (0 is missing, 1 is lodash-php library, 2 is php-lodash library, and 3 is from this repo)
        $lodashList = [
            // Array
            ['chunk', 1],
            ['compact', 1],
            ['concat', 1],
            ['difference', 1],
            ['differenceBy', 0],
            ['differenceWith', 0],
            ['drop', 1],
            ['dropRight', 1],
            ['dropRightWhile', 0],
            ['dropWhile', 0],
            ['findIndex', 1],
            ['findLastIndex', 1],
            ['first', 2],
            ['flatten', 1],
            ['flattenDeep', 1],
            ['flattenDepth', 1],
            ['fromPairs', 3],
            ['head', 1],
            ['indexOf', 1],
            ['initial', 1],
            ['intersection', 1],
            ['intersectionBy', 0],
            ['intersectionWith', 0],
            ['join', 3],
            ['last', 1],
            ['lastIndexOf', 1],
            ['nth', 1],
            ['slice', 3],
            ['sortedIndex', 3],
            ['sortedIndexBy', 0],
            ['sortedIndexOf', 3],
            ['sortedLastIndex', 3],
            ['sortedLastIndexBy', 0],
            ['sortedLastIndexOf', 3],
            ['sortedUniq', 3],
            ['sortedUniqBy', 0],
            ['tail', 1],
            ['take', 1],
            ['takeRight', 1],
            ['takeRightWhile', 1],
            ['takeWhile', 1],
            ['union', 1],
            ['unionBy', 0],
            ['unionWith', 0],
            ['uniq', 1],
            ['uniqBy', 0],
            ['uniqWith', 0],
            ['unzip', 1],
            ['unzipWith', 0],
            ['without', 1],
            ['xor', 0],
            ['xorBy', 0],
            ['xorWith', 0],
            ['zip', 1],
            ['zipObject', 1],
            ['zipObjectDeep', 1],
            ['zipWith', 0],
            // Collection
            ['countBy', 0],
            ['every', 1],
            ['filter', 1],
            ['find', 1],
            ['findLast', 1],
            ['flatMap', 0],
            ['flatMapDeep', 0],
            ['flatMapDepth', 0],
            ['groupBy', 0], //overlap
            ['includes', 3],
            ['invokeMap', 0],
            ['keyBy', 1],
            ['map', 1],
            ['orderBy', 3],
            ['partition', 0],
            ['reduce', 0], //overlap
            ['reduceRight', 0], //overlap
            ['reject', 1],
            ['sample', 1], //untested
            ['sampleSize', 1], //untested
            ['shuffle', 1], //untested
            ['size', 1],
            ['some', 1],
            ['sortBy', 1],
            // Date
            ['now', 1],
            // Function
            ['flip', 0],
            ['negate', 0],
            ['overArgs', 0],
            ['partial', 0],
            ['partialRight', 0],
            ['rearg', 0],
            ['rest', 0],
            ['spread', 0],
            // Lang
            ['castArray', 3],
            ['clone', 0],
            ['cloneDeep', 0],
            ['cloneDeepWith', 0],
            ['conformsTo', 0],
            ['eq', 1],
            ['gt', 3],
            ['gte', 3],
            ['isArguments', 0],
            ['isArray', 2],
            ['isArrayBuffer', 0],
            ['isArrayLike', 3],
            ['isArrayLikeObject', 3],
            ['isBoolean', 3],
            ['isBuffer', 0],
            ['isDate', 0],
            ['isElement', 0],
            ['isEmpty', 2],
            ['isEqual', 1],
            ['isEqualWith', 0],
            ['isError', 1],
            ['isFinite', 3],
            ['isFunction', 0],
            ['isInteger', 3],
            ['isLength', 3],
            ['isMap', 0],
            ['isMatch', 3],
            ['isMatchWith', 0],
            ['isNaN', 3],
            ['isNative', 0],
            ['isNil', 0],
            ['isNull', 2],
            ['isNumber', 3],
            ['isObject', 3],
            ['isObjectLike', 0],
            ['isPlainObject', 0],
            ['isRegExp', 0],
            ['isSafeInteger', 0],
            ['isSet', 0],
            ['isString', 2],
            ['isSymbol', 0],
            ['isTypedArray', 0],
            ['isUndefined', 0],
            ['isWeakMap', 0],
            ['isWeakSet', 0],
            ['lt', 3],
            ['lte', 3],
            ['toArray', 3],
            ['toFinite', 3],
            ['toInteger', 3],
            ['toLength', 3],
            ['toNumber', 3],
            ['toPlainObject', 0],
            ['toSafeInteger', 3],
            ['toString', 3],
            // Math
            ['add', 1],
            ['ceil', 3],
            ['divide', 3],
            ['floor', 3],
            ['max', 1],
            ['maxBy', 1],
            ['mean', 3],
            ['meanBy', 0],
            ['min', 2],
            ['minBy', 0],
            ['multiply', 3],
            ['round', 3],
            ['subtract', 3],
            ['sum', 3],
            ['sumBy', 0],
            // Number
            ['clamp', 1],
            ['inRange', 1],
            ['random', 1], //untested
            // Object
            ['at', 3],
            ['entries', 3],
            ['entriesIn', 3],
            ['findKey', 0],
            ['findLastKey', 0],
            ['functions', 0],
            ['functionsIn', 0],
            ['get', 1],
            ['has', 3],
            ['hasIn', 3],
            ['invert', 3],
            ['invertBy', 0],
            ['invoke', 0],
            ['keys', 3],
            ['keysIn', 3],
            ['mapKeys', 0], //2nd
            ['mapValues', 0], //2nd
            ['omit', 3],
            ['omitBy', 0],
            ['pick', 2],
            ['pickBy', 0],
            ['result', 3],
            ['toPairs', 3],
            ['toPairsIn', 3],
            ['transform', 0],
            ['values', 3],
            ['valuesIn', 3],
            // String
            ['camelCase', 1],
            ['capitalize', 1],
            ['deburr', 1],
            ['endsWith', 1],
            ['escape', 1],
            ['escapeRegExp', 1],
            ['kebabCase', 1],
            ['lowerCase', 1],
            ['lowerFirst', 1],
            ['pad', 1],
            ['padEnd', 1],
            ['padStart', 1],
            ['parseInt', 1],
            ['repeat', 1],
            ['replace', 1],
            ['snakeCase', 1],
            ['split', 1],
            ['startCase', 1],
            ['startsWith', 1],
            ['toLower', 1],
            ['toUpper', 1],
            ['trim', 1],
            ['trimEnd', 1],
            ['trimStart', 1],
            ['truncate', 1],
            ['unescape', 1],
            ['upperCase', 1],
            ['upperFirst', 1],
            ['words', 1],
            // Util
            ['cond', 0],
            ['conforms', 0],
            ['constant', 3],
            ['defaultTo', 1],
            ['flow', 0],
            ['flowRight', 0],
            ['identity', 1],
            ['iteratee', 3],
            ['matches', 3],
            ['matchesProperty', 3],
            ['method', 0],
            ['methodOf', 0],
            ['nthArg', 0],
            ['over', 0],
            ['overEvery', 0],
            ['overSome', 0],
            ['property', 1],
            ['propertyOf', 0],
            ['range', 3],
            ['rangeRight', 3],
            ['stubArray', 3],
            ['stubFalse', 3],
            ['stubObject', 3],
            ['stubString', 3],
            ['stubTrue', 3],
            ['times', 3],
            ['toPath', 3],
            ['uniqueId', 0],
        ];
        foreach ($lodashList as $lodashfunct)
        {
            if($lodashfunct[1] == 1)
            {
                JsonLogic::add_operation('_'.$lodashfunct[0], '_::'.$lodashfunct[0]);
            }
            if ($lodashfunct[1] == 2)
            {
                JsonLogic::add_operation('_'.$lodashfunct[0], '__::'.$lodashfunct[0]);
            }
            if ($lodashfunct[1] == 3)
            {
                JsonLogic::add_operation('_'.$lodashfunct[0], 'Northwestern\SysDev\DynamicForms\Conditional\LodashFunctions\___::'.$lodashfunct[0]);
            }
        }
    }


}
