<?php

namespace Northwestern\SysDev\DynamicForms\Resources;

interface ResourceInterface
{
    /**
     * Will provide the name of the Resource type.
     */
    public static function indexName(): string;

    /**
     * Will provide the list of components that will contain the Resource.
     */
    public static function components(): array;

    /**
     * Will provide the submissions that backs this resource,
     * will be a nested array where each sub array represents one submission value
     * with the component key as the key and the value as the value
     * each component is optional unless described otherwise in components()
     * If handlesPaginationAndSearch is false then the arguments can be ignored
     * otherwise must paginate data according to the arguments.
     * @param int $limit the (maximum) size of the returned array, if -1 then no limit
     * @param int $skip the entrie index to start at for the returned data
     * @param string $key the label to search by or '' if the entire object should be searched
     * @param string $needle the value to search for or '' if there is no search
     */
    public static function submissions(int $limit, int $skip, string $key, string $needle): array;

    /**
     * Returns if this Resource will handle pagination and search when submissions() is called
     * If false then full data will be provided on each call to submission.
     */
    public static function handlesPaginationAndSearch(): bool;
}
