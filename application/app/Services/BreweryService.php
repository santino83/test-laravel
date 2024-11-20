<?php

namespace App\Services;

use App\Brewery\BreweryClient;
use App\Brewery\DTO\BreweryDTO;
use App\Brewery\DTO\BreweryListDTO;
use Illuminate\Support\Facades\Log;

class BreweryService
{

    public function __construct(protected BreweryClient $client){}

    /**
     * Gets a list of breweries
     *
     * @param int $page
     * @param int $perPage
     * @return BreweryListDTO
     */
    public function listAll(int $page = 1, int $perPage = 20): BreweryListDTO
    {
        try{
            return $this->client->breweries()->list($perPage, $page);
        }catch (\Throwable $ex){
            Log::error(self::class.'::listAll() error: '.$ex->getMessage());
            return new BreweryListDTO($page, $perPage);
        }
    }

    /**
     * Gets a single brewery by id
     *
     * @param string $id
     * @return BreweryDTO|null
     */
    public function get(string $id): BreweryDTO|null
    {
        try{
            return $this->client->breweries()->byId($id);
        }catch (\Throwable $ex){
            Log::error(self::class.'::get(id) error: '.$ex->getMessage());
            return null;
        }
    }

}
