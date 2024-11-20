<?php

namespace App\Brewery\DTO;

use JMS\Serializer\Annotation\SerializedName;

class BreweryDTO
{

    public ?string $id = null;

    public ?string $name = null;

    #[SerializedName('brewery_type')]
    public ?string $breweryType = null;
    #[SerializedName('address_1')]
    public ?string $address1 = null;

    #[SerializedName('address_2')]
    public ?string $address2 = null;

    #[SerializedName('address_3')]
    public ?string $address3 = null;

    public ?string $city = null;

    #[SerializedName('state_province')]
    public ?string $stateProvince  = null;

    #[SerializedName('postal_code')]
    public ?string $postalCode = null;

    public ?string $country = null;

    public ?string $longitude = null;

    public ?string $latitude = null;

    public ?string $phone = null;

    #[SerializedName('website_url')]
    public ?string $websiteUrl = null;

    public ?string $state = null;

    public ?string $street = null;

}
