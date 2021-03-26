<?php

namespace Northwestern\SysDev\DynamicForms\Components\Layout;

use Illuminate\Support\Arr;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\Components\CustomSubcomponentDeserialization;

class Columns extends BaseComponent implements CustomSubcomponentDeserialization
{
    const TYPE = 'columns';

    public function canValidate(): bool
    {
        return false;
    }

    public static function pathsToChildren(array $component): array
    {
        $paths = [];

        $columns = Arr::get($component, 'columns', []);
        foreach ($columns as $columnIndex => $column) {
            $paths[] = sprintf('columns.%s', $columnIndex);
        }

        return $paths;
    }
}
