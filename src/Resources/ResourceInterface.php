<?php


namespace Northwestern\SysDev\DynamicForms\Resources;


interface ResourceInterface
{
    /**
     * Will provide the name of the Resource type
     */
    public static function indexName() : string;

    /**
     * Will provide the list of components that will contain the Resource
     */
    public static function components() : string;


}