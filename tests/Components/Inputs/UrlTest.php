<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Url;
use Northwestern\SysDev\DynamicForms\Tests\Components\TestCases\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Url
 */
class UrlTest extends InputComponentTestCase
{
    protected string $componentClass = Url::class;

    public function validationsProvider(): array
    {
        return [
            'passes when no value is supplied' => [[], '', true],
            'invalid url fails' => [[], 'dog', false],
            'valid url passes' => [[], 'http://google.com', true],
            'required passes' => [['required' => true], 'https://google.com', true],
            'required fails' => [['required' => true], '', false],
            'minLength fails' => [['minLength' => 20], 'http://google.com', false],
            'minLength passes' => [['minLength' => 17], 'http://google.com', true],
            'maxLength fails' => [['maxLength' => 3], 'http://nu.edu/foo/bar.php', false],
            'maxLength passes' => [['maxLength' => 300], 'http://nu.edu/foo/bar.php', true],
            'minWords fails' => [['minWords' => 2], 'http://google.com', false],
            'minWords passes' => [['minWords' => 1], 'http://google.com', true],
            'regex passes' => [['pattern' => 'reddit\.com'], 'https://reddit.com', true],
            'regex fails' => [['pattern' => 'reddit\.com'], 'https://google.com', false],
        ];
    }

    public function submissionValueProvider(): array
    {
        $url = 'https://google.com';

        return [
            'no transformations' => [null, $url, $url],
            'upper' => [CaseEnum::UPPER, $url, $url],
            'lower' => [CaseEnum::LOWER, $url, $url],
        ];
    }
}
