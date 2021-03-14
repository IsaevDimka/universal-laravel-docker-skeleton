<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends ApiController
{
    public function index(Request $request)
    {
        $items = CityResource::items(City::query()->paginate($request->get('limit')));
        return api()->ok(null, array_merge(compact('items'), $items->pagination));
    }

    public function show(City $city)
    {
        return api()->ok(null, CityResource::make($city));
    }
}
