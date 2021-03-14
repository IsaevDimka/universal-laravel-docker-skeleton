<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Traits\QueryFilterByRequest;
use Illuminate\Http\Request;

class CountryController extends ApiController
{
    use QueryFilterByRequest;

    public function index(Request $request)
    {
        $queryBuilder = Country::query();

        $queryBuilder->when(! $request->has('withRaw'), fn ($q) => $q->select(['id', 'name_common', 'name_official', 'iso_code']));

        $rules = Country::rules();
        $queryBuilder = $this->queryFilterByRequest($request, $rules, $queryBuilder);
        $filters = $request->only(array_keys($rules));

        $items = CountryResource::items($queryBuilder->paginate($request->get('limit')));
        return api()->ok(null, array_merge(compact('items', 'filters'), $items->pagination));
    }

    public function show(int $id)
    {
        $country = Country::findOrFail($id);
        return api()->ok(null, $country);
    }

    public function getByIsoCode(string $iso_code)
    {
        $country = Country::where('iso_code', '=', strtoupper($iso_code))->firstOrFail();
        return api()->ok(null, CountryResource::make($country));
    }
}
