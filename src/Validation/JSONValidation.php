<?php

namespace Northwestern\SysDev\DynamicForms\Validation;

use Illuminate\Support\MessageBag;
use Illuminate\Support\MessageBag as MessageBagImpl;
use JWadhams\JsonLogic;
use Northwestern\SysDev\DynamicForms\Components\ComponentInterface;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionCheckResult;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssue;
use Northwestern\SysDev\DynamicForms\Forms\Definition\DefinitionIssueType;
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
                DefinitionIssueType::JsonValidationInvalidStructure->description(),
                $component->key(),
            );
        }

        $validationResult = JsonLogic::apply($this->jsonLogic, $submissionValues);

        if ($validationResult !== true) {
            // There SHOULD be a true & error message case, but it's possible to screw it up.
            // If the return value is false/null/0/"", provide *something* as a breadcrumb to help troubleshoot.
            if (! $validationResult) {
                $validationResult = "{$component->label()} was not valid.";
            }

            $bag->add($component->key(), $validationResult);
        }

        return $bag;
    }

    public function checkDefinition(ComponentInterface $component): DefinitionCheckResult
    {
        if (! $this->isValidCustomValidation($this->jsonLogic)) {
            return new DefinitionCheckResult([
                $this->issue($component, DefinitionIssueType::JsonValidationInvalidStructure),
            ]);
        }

        $outputs = array_slice(array_values($this->jsonLogic['if']), 1);
        if (in_array(true, $outputs, true)) {
            return new DefinitionCheckResult;
        }

        return new DefinitionCheckResult([
            $this->issue($component, DefinitionIssueType::JsonValidationMissingTrue),
        ]);
    }

    private function issue(ComponentInterface $component, DefinitionIssueType $type): DefinitionIssue
    {
        return new DefinitionIssue(
            componentKey: $component->key(),
            componentLabel: $component->label(),
            jsonPath: 'validate.json.if',
            type: $type,
        );
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
