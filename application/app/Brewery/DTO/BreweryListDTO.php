<?php

namespace App\Brewery\DTO;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

class BreweryListDTO
{

    #[SerializedName('per_page')]
    public int $perPage = 20;

    public int $page = 1;

    #[Type('array<'.BreweryDTO::class.'>')]
    public array $list = [];

    /**
     * @param int $perPage
     * @param int $page
     */
    public function __construct(int $page = 1, int $perPage = 20)
    {
        $this->perPage = $perPage;
        $this->page = $page;
    }


}
