<?php

namespace Tests;

use App\Brewery\BreweryContext;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    protected function getContext(): BreweryContext
    {
        return BreweryContext::create()->build();
    }

}
