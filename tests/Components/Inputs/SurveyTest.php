<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Survey;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Survey
 */
final class SurveyTest extends InputComponentTestCase
{
    protected string $componentClass = Survey::class;
    protected array $defaultAdditional = [
        'questions' => [
            ['label' => 'Question 1', 'value' => 'q1'],
            ['label' => 'Question 2', 'value' => 'q2'],
            ['label' => 'Question 3 (i.e. foo bar)', 'value' => 'Question 3 (i.e. foo bar)'],
        ],
        'values' => [
            ['label' => 'Answer 1', 'value' => 'a1'],
            ['label' => 'Answer 2', 'value' => 'a2'],
            ['label' => 'Answer 3 with no trim ', 'value' => 'notrim '],
        ],
    ];

    /**
     * @covers ::questions
     * @covers ::questionsWithLabels
     */
    public function testQuestions(): void
    {
        $this->assertEquals(
            ['q1', 'q2', 'Question 3 (i.e. foo bar)'],
            $this->getSurvey()->questions()
        );

        $this->assertEquals(
            ['q1' => 'Question 1', 'q2' => 'Question 2', 'Question 3 (i.e. foo bar)' => 'Question 3 (i.e. foo bar)'],
            $this->getSurvey()->questionsWithLabels(),
        );
    }

    /**
     * @covers ::validChoices
     * @covers ::choicesWithLabels
     */
    public function testValidChoices(): void
    {
        $this->assertEquals(
            ['a1', 'a2', 'notrim'],
            $this->getSurvey()->validChoices()
        );

        $this->assertEquals(
            ['a1' => 'Answer 1', 'a2' => 'Answer 2', 'notrim ' => 'Answer 3 with no trim '],
            $this->getSurvey()->choicesWithlabels()
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
            'empty data passes' => [[], ['q1' => '', 'q2' => '', 'Question 3 (i.e. foo bar)' => ''], true],
            'invalid value fails' => [[], ['q1' => 'invalid', 'q2' => 'a1', 'Question 3 (i.e. foo bar)' => 'a1'], false],
            'required passes' => [['required' => true], ['q1' => 'a2', 'q2' => 'a1', 'Question 3 (i.e. foo bar)' => 'a1'], true],
            'one missing - required fails' => [['required' => true], ['qa' => '', 'q2' => 'a1', 'Question 3 (i.e. foo bar)' => 'a1'], false],
            'all missing - required fails' => [['required' => true], ['qa' => '', 'q2' => '', 'Question 3 (i.e. foo bar)' => ''], false],
            'passes with trim' => [['required' => true], ['q1' => 'a1', 'q2' => 'a1', 'Question 3 (i.e. foo bar)' => 'notrim'], true], // Laravel middleware would trim form value
        ];
    }

    public function submissionValueProvider(): array
    {
        $responses = ['q1' => 'a2', 'q2' => 'a1', 'Question 3 (i.e. foo bar)' => 'a1'];

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
