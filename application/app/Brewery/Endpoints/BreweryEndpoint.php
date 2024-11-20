<?php

namespace App\Brewery\Endpoints;

use App\Brewery\DTO\BreweryDTO;
use App\Brewery\DTO\BreweryListDTO;

class BreweryEndpoint extends AbstractEndpoint
{

    public const string ENDPOINT = '/v1/breweries';

    /**
     * Get a list of breweries
     *
     * @param int $perPage
     * @param int $page
     * @return BreweryDTO[]
     * @throws \Throwable
     */
    public function list(int $perPage = 20, int $page = 1) : BreweryListDTO
    {
        $page = max($page, 1);
        $perPage = max($perPage, 1);
        $url = sprintf('%s?per_page=%d&page=%d', self::ENDPOINT, $perPage, $page);

        $result = $this->send(self::GET, $url);

        if (is_int($result)) throw new \RuntimeException("Unexpected int response " . $result);

        $dto = $this->getMapper()->deserialize($result, 'array<'.BreweryDTO::class.'>');

        $list = new BreweryListDTO();
        $list->page = $page;
        $list->perPage = $perPage;
        $list->list = $dto;

        return $list;
    }

    /**
     * Get a single brewery by id
     *
     * @param string $id
     * @return BreweryDTO|null
     * @throws \Throwable
     */
    public function byId(string $id): BreweryDTO|null
    {
        $url = sprintf('%s/%s', self::ENDPOINT, $id);

        $result = $this->send(self::GET, $url);

        if (is_int($result)) throw new \RuntimeException("Unexpected int response " . $result);

        try{
            return $this->getMapper()->deserialize($result, BreweryDTO::class);
        }catch (\Throwable $ex){
            return null;
        }
    }

}
