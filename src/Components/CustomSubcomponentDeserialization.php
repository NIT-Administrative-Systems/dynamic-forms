<?php

namespace Northwestern\SysDev\DynamicForms\Components;

/**
 * A Component should implement this if its children are not kept in the typical "components" key.
 */
interface CustomSubcomponentDeserialization
{
    /**
     * Produces an instance from the (json_decode()'d) JSON.
     *
     * @param array $component The json_decode()'d segment for the component
     * @return string[] Array dot-notation paths
     */
    public static function pathsToChildren(array $component): array;
}
