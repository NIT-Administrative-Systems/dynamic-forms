<?php

namespace Northwestern\SysDev\DynamicForms\Tests\Components\Inputs;

use Northwestern\SysDev\DynamicForms\Components\CaseEnum;
use Northwestern\SysDev\DynamicForms\Components\Inputs\Address;
use Northwestern\SysDev\DynamicForms\Errors\InvalidDefinitionError;
use Northwestern\SysDev\DynamicForms\Tests\Components\InputComponentTestCase;

/**
 * @coversDefaultClass \Northwestern\SysDev\DynamicForms\Components\Inputs\Address
 */
class AddressTest extends InputComponentTestCase
{
    public string $componentClass = Address::class;
    public array $defaultAdditional = ['provider' => Address::PROVIDER_OPENSTREETMAP];

    const VALID_ADDR = [
        'place_id' => 102103665,
        'licence' => 'Data © OpenStreetMap contributors, ODbL 1.0. https://osm.org/copyright',
        'osm_type' => 'way',
        'osm_id' => 42706487,
        'boundingbox' => [
            '42.0500749',
            '42.050507',
            '-87.6825641',
            '-87.6819604',
        ],
        'lat' => '42.0502499',
        'lon' => '-87.68226336219436',
        'display_name' => '1800 Sherman Avenue, 1800, Sherman Avenue, Downtown, Evanston, Evanston Township, Cook County, Illinois, 60201, United States',
        'class' => 'building',
        'type' => 'office',
        'importance' => 0.31100000000000005,
        'address' => [
            'building' => '1800 Sherman Avenue',
            'house_number' => '1800',
            'road' => 'Sherman Avenue',
            'neighbourhood' => 'Downtown',
            'town' => 'Evanston',
            'municipality' => 'Evanston Township',
            'county' => 'Cook County',
            'state' => 'Illinois',
            'postcode' => '60201',
            'country' => 'United States',
            'country_code' => 'us',
        ],
    ];

    public function testUnsupportedProviderThrowsError(): void
    {
        $this->expectException(InvalidDefinitionError::class);

        $this->getComponent(additional: ['provider' => Address::PROVIDER_AZURE]);
    }

    public function validationsProvider(): array
    {
        return [
            'no data passes' => [[], [], true],
            'required passes' => [['required' => true], self::VALID_ADDR, true],
            'required fails' => [['required' => true], [], false],
        ];
    }

    public function submissionValueProvider(): array
    {
        return [
            'no transformations' => [null, self::VALID_ADDR, self::VALID_ADDR],
            'upper' => [CaseEnum::UPPER, self::VALID_ADDR, self::VALID_ADDR],
            'lower' => [CaseEnum::LOWER, self::VALID_ADDR, self::VALID_ADDR],
        ];
    }
}
