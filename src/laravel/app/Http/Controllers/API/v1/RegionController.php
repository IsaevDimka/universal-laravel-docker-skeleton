<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;
use App\Http\Resources\RegionResource;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends ApiController
{
    public function index(Request $request)
    {
        $items = RegionResource::items(Region::query()->paginate($request->get('limit')));
        return api()->ok(null, array_merge(compact('items'), $items->pagination));
    }

    public function show(Region $region)
    {
        return api()->ok(null, RegionResource::make($region));
    }
}
