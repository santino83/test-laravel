<?php

namespace App\Brewery;

use App\Brewery\Endpoints\BreweryEndpoint;

class BreweryClient
{

    public function __construct(protected BreweryContext $context){}

    public function breweries(): BreweryEndpoint
    {
        return new BreweryEndpoint($this->context);
    }

}
