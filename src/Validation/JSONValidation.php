<?php

namespace Northwestern\SysDev\DynamicForms\Validation;

use Illuminate\Support\MessageBag;
use Illuminate\Support\MessageBag as MessageBagImpl;
use JWadhams\JsonLogic;
use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;
use Northwestern\SysDev\DynamicForms\JSONLogic\JsonLogicHelpers;

class JSONValidation implements ValidationInterface
{
    protected array $jsonLogic;

    public function __construct(array $jsonLogic)
    {
        $this->jsonLogic = JsonLogicHelpers::convertDataVars($jsonLogic);
    }

    public function __invoke(ComponentInterface $component, array $submissionValues): MessageBag
    {
        $bag = new MessageBagImpl;

        if (! $this->isValidCustomValidation($this->jsonLogic)) {
            throw new InvalidDefinitionError(
                'Custom JSON Logic validations must always use the "if" parameter. The first argument to the statement must be the "true" case, and the second should be the error to display if the validation fails.',
                $component->key(),
            );
        }

        $validationResult = JsonLogic::apply($this->jsonLogic, $submissionValues);

        if ($validationResult !== true) {
            $bag->add($component->key(), $validationResult);
        }

        return $bag;
    }

    /**
     * Custom JSON Logic validations must always use the "if" parameter. The first argument to the statement
     * must be the "true" case, and the second should be the error to display if the validation fails.
     *
     * {@link https://help.form.io/developers/form-development/form-evaluations#custom-validation-1}
     */
    protected function isValidCustomValidation(array $jsonLogic): bool
    {
        return isset($jsonLogic['if']) &&
            is_array($jsonLogic['if']) &&
            count($jsonLogic['if']) === 3;
    }
}
