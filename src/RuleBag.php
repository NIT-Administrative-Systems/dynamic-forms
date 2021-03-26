<?php

namespace Northwestern\SysDev\DynamicForms;

use Illuminate\Contracts\Validation\Rule;

class RuleBag
{
    public function __construct(
        protected string $key,
        protected array $bag
    ) {
    }

    public function add(string | array | Rule $rule): bool
    {
        $this->bag[] = $rule;

        return true;
    }

    public function addIfNotNull(string | array | Rule $rule, mixed $condition): bool
    {
        return $this->addIf($rule, $condition !== null);
    }

    public function addIf(string | array | Rule $rule, bool $condition): bool
    {
        return $condition
            ? $this->add($rule)
            : false;
    }

    public function rules(): array
    {
        return [$this->key => $this->bag];
    }
}
