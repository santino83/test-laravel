<?php

namespace App\Http\Api;

use App\Services\BreweryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BreweriesController
{

    public function __construct(protected BreweryService $service)
    {
    }

    /**
     * Gets a list of breweries
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listAll(Request $request): JsonResponse
    {
        $page = max(1, (int)$request->get('page', 1));
        $perPage = max(1, (int)$request->get('per_page', 20));

        $result = $this->service->listAll($page, $perPage);

        return response()->json($result);
    }

    /**
     * Gets a single brewery by id
     *
     * @param string $id
     * @return JsonResponse
     */
    public function get(string $id): JsonResponse
    {
        $result = $this->service->get($id);
        return response()->json($result);
    }

}
