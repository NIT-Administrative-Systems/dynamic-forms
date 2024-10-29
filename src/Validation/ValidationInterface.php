<?php

namespace Northwestern\SysDev\DynamicForms\Validation;

use Illuminate\Contracts\Support\MessageBag;
use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;

interface ValidationInterface
{
    public function __invoke(ComponentInterface $component, array $submissionValues): MessageBag;
}
