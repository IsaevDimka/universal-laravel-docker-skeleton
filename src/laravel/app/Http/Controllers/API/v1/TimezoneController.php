<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;
use App\Http\Resources\TimezoneResource;
use App\Models\Timezone;
use App\Traits\QueryFilterByRequest;
use Illuminate\Http\Request;

class TimezoneController extends ApiController
{
    use QueryFilterByRequest;

    public function index(Request $request)
    {
        $queryBuilder = Timezone::query();
        $rules = Timezone::rules();

        $queryBuilder = $this->queryFilterByRequest($request, $rules, $queryBuilder);
        $filters = $request->only(array_keys($rules));

        $items = TimezoneResource::items($queryBuilder->paginate($request->get('limit')));
        return api()->ok(null, array_merge(compact('items', 'filters'), $items->pagination));
    }
}
