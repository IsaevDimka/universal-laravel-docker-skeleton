<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Traits\QueryFilterByRequest;
use Illuminate\Http\Request;

class CurrencyController extends ApiController
{
    use QueryFilterByRequest;

    public function index(Request $request)
    {
        $queryBuilder = Currency::query();
        $rules = Currency::rules();
        $queryBuilder = $this->queryFilterByRequest($request, $rules, $queryBuilder);
        $filters = $request->only(array_keys($rules));

        $items = CurrencyResource::items($queryBuilder->paginate($request->get('limit')));
        return api()->ok(null, array_merge(compact('items', 'filters'), $items->pagination));
    }

    public function show(int $id)
    {
        $currency = Currency::findOrFail($id);
        return api()->ok(null, CurrencyResource::make($currency));
    }

    public function getByIsoCode(string $iso_code)
    {
        $currency = Currency::where('iso_code', '=', strtoupper($iso_code))->firstOrFail();
        return api()->ok(null, CurrencyResource::make($currency));
    }
}
