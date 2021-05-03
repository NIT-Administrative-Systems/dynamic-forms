<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Survey;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Survey
 */
class SurveyTest extends InputComponentTestCase
{
    protected string $componentClass = Survey::class;
    protected array $defaultAdditional = [
        'questions' => [
            ['label' => 'Question 1', 'value' => 'q1'],
            ['label' => 'Question 2', 'value' => 'q2'],
        ],
        'values' => [
            ['label' => 'Answer 1', 'value' => 'a1'],
            ['label' => 'Answer 2', 'value' => 'a2'],
        ],
    ];

    /**
     * @covers ::questions
     */
    public function testQuestions(): void
    {
        $this->assertEquals(
            ['q1', 'q2'],
            $this->getSurvey()->questions()
        );
    }

    /**
     * @covers ::validChoices
     */
    public function testValidChoices(): void
    {
        $this->assertEquals(
            ['a1', 'a2'],
            $this->getSurvey()->validChoices()
        );
    }

    public function testSubmissionValueStripsExtras(): void
    {
        $survey = $this->getSurvey();
        $survey->setSubmissionValue([
            'q1' => 'a',
            'q2' => 'a',
            'invalid' => 'a',
        ]);

        $this->assertEquals(['q1' => 'a', 'q2' => 'a'], $survey->submissionValue());
    }

    public function validationsProvider(): array
    {
        return [
            'empty data passes' => [[], ['q1' => '', 'q2' => ''], true],
            'invalid value fails' => [[], ['q1' => 'invalid', 'q2' => 'a1'], false],
            'required passes' => [['required' => true], ['q1' => 'a2', 'q2' => 'a1'], true],
            'one missing - required fails' => [['required' => true], ['qa' => '', 'q2' => 'a1'], false],
            'all missing - required fails' => [['required' => true], ['qa' => '', 'q2' => ''], false],

        ];
    }

    public function submissionValueProvider(): array
    {
        $responses = ['q1' => 'a2', 'q2' => 'a1'];

        return [
            'no transformations' => [null, $responses, $responses],
            'upper' => [CaseEnum::UPPER, $responses, $responses],
            'lower' => [CaseEnum::LOWER, $responses, $responses],
        ];
    }

    /**
     * Helper to make return type more specific.
     */
    public function getSurvey(): Survey
    {
        return $this->getComponent();
    }
}
