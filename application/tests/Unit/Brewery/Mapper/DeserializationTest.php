<?php

namespace Tests\Unit\Brewery\Mapper;

use App\Brewery\DTO\BreweryDTO;
use App\Brewery\Mapper\Mapper;
use Tests\TestCase;

class DeserializationTest extends TestCase
{

    protected Mapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = $this->getContext()->getMapper();
    }

    public function test_breweries_deserialization(): void
    {
        $json = <<<JSON
[
    {
    "id": "b54b16e1-ac3b-4bff-a11f-f7ae9ddc27e0",
    "name": "MadTree Brewing 2.0",
    "brewery_type": "regional",
    "address_1": "5164 Kennedy Ave",
    "address_2": null,
    "address_3": null,
    "city": "Cincinnati",
    "state_province": "Ohio",
    "postal_code": "45213",
    "country": "United States",
    "longitude": "-84.4137736",
    "latitude": "39.1885752",
    "phone": "5138368733",
    "website_url": "http://www.madtreebrewing.com",
    "state": "Ohio",
    "street": "5164 Kennedy Ave"
},
    {
        "id": "9c5a66c8-cc13-416f-a5d9-0a769c87d318",
        "name": "(512) Brewing Co",
        "brewery_type": "micro",
        "address_1": "407 Radam Ln Ste F200",
        "address_2": null,
        "address_3": null,
        "city": "Austin",
        "state_province": "Texas",
        "postal_code": "78745-1197",
        "country": "United States",
        "longitude": null,
        "latitude": null,
        "phone": "5129211545",
        "website_url": "http://www.512brewing.com",
        "state": "Texas",
        "street": "407 Radam Ln Ste F200"
    },
    {
        "id": "34e8c68b-6146-453f-a4b9-1f6cd99a5ada",
        "name": "1 of Us Brewing Company",
        "brewery_type": "micro",
        "address_1": "8100 Washington Ave",
        "address_2": null,
        "address_3": null,
        "city": "Mount Pleasant",
        "state_province": "Wisconsin",
        "postal_code": "53406-3920",
        "country": "United States",
        "longitude": "-87.88336350209435",
        "latitude": "42.72010826899558",
        "phone": "2624847553",
        "website_url": "https://www.1ofusbrewing.com",
        "state": "Wisconsin",
        "street": "8100 Washington Ave"
    }
]
JSON;

        $obj = $this->mapper->deserialize($json, 'array<'.BreweryDTO::class.'>');
        $this->assertNotNull($obj);
        $this->assertTrue(is_array($obj));
        $this->assertTrue(count($obj) === 3);

        $this->validateOne($obj[0]);
    }

    public function test_brewery_deserialization(): void
    {
        $json = <<<JSON
{
    "id": "b54b16e1-ac3b-4bff-a11f-f7ae9ddc27e0",
    "name": "MadTree Brewing 2.0",
    "brewery_type": "regional",
    "address_1": "5164 Kennedy Ave",
    "address_2": null,
    "address_3": null,
    "city": "Cincinnati",
    "state_province": "Ohio",
    "postal_code": "45213",
    "country": "United States",
    "longitude": "-84.4137736",
    "latitude": "39.1885752",
    "phone": "5138368733",
    "website_url": "http://www.madtreebrewing.com",
    "state": "Ohio",
    "street": "5164 Kennedy Ave"
}
JSON;

        $obj = $this->mapper->deserialize($json, BreweryDTO::class);
        $this->assertNotNull($obj);
        $this->assertInstanceOf(BreweryDTO::class, $obj);

        $this->validateOne($obj);

    }

    private function validateOne(BreweryDTO $obj): void
    {
        $this->assertEquals("b54b16e1-ac3b-4bff-a11f-f7ae9ddc27e0", $obj->id);
        $this->assertEquals("MadTree Brewing 2.0", $obj->name);
        $this->assertEquals("regional", $obj->breweryType);
        $this->assertEquals('5164 Kennedy Ave', $obj->address1);
        $this->assertNull($obj->address2);
        $this->assertNull($obj->address3);
        $this->assertEquals("Cincinnati", $obj->city);
        $this->assertEquals("Ohio", $obj->stateProvince);
        $this->assertEquals("45213", $obj->postalCode);
        $this->assertEquals("United States", $obj->country);
        $this->assertEquals("-84.4137736", $obj->longitude);
        $this->assertEquals("39.1885752", $obj->latitude);
        $this->assertEquals("http://www.madtreebrewing.com", $obj->websiteUrl);
        $this->assertEquals("Ohio", $obj->state);
        $this->assertEquals("5164 Kennedy Ave", $obj->street);
    }

}
