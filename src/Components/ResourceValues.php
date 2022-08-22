<?php

namespace Northwestern\SysDev\DynamicForms\Components;

use Northwestern\SysDev\DynamicForms\ResourceRegistry;

interface ResourceValues
{
    public function getResourceRegistry(): ResourceRegistry;

    public function setResourceRegistry(ResourceRegistry $resourceRegistry): void;

    public function setOptionValues(): void;
}
