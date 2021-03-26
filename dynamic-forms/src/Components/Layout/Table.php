<?php

namespace Northwestern\SysDev\DynamicForms\Components\Layout;

use Illuminate\Support\Arr;
use Northwestern\SysDev\DynamicForms\Components\BaseComponent;
use Northwestern\SysDev\DynamicForms\Components\CustomSubcomponentDeserialization;

class Table extends BaseComponent implements CustomSubcomponentDeserialization
{
    const TYPE = 'table';

    public function canValidate(): bool
    {
        return false;
    }

    public static function pathsToChildren(array $component): array
    {
        $paths = [];

        $rows = Arr::get($component, 'rows', []);
        foreach ($rows as $rowIndex => $row) {
            foreach ($row as $cellIndex => $cell) {
                $paths[] = sprintf('rows.%s.%s', $rowIndex, $cellIndex);
            }
        }

        return $paths;
    }
}
