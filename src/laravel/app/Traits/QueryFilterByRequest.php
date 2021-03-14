<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

trait QueryFilterByRequest
{
    use ValidatesRequests;

    public function queryFilterByRequest(Request $request, array $rules = [], Builder $queryBuilder): Builder
    {
        /** @todo: improvement for support datetime format */

        $filters = $this->validate($request, $rules);

        $allowed_filters = array_only($filters, array_keys($rules));
        foreach ($allowed_filters as $filterKey => $filterVal) {
            switch (array_last(array_get($rules, $filterKey))) {
                case 'boolean':
                case 'integer':
                case 'float':
                    $operator = '=';
                    $value = $filterVal;
                break;
                case 'string':
                default:
                    $operator = 'ilike';
                    $value = '%' . $filterVal . '%';
            }
            if (! is_null($filterVal)) {
                $queryBuilder->where($filterKey, $operator, $value);
            }
        }

        $queryBuilder->when($request->has('with'), fn ($q) => $q->with($request->get('with')));
        $queryBuilder->when($request->has('withCount'), fn ($q) => $q->withCount($request->get('withCount')));

        return $queryBuilder;
    }
}
