<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;
use App\Services\FilterService;

class FilterKeysByModelController extends ApiController
{
    protected FilterService $filterService;

    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    public function __invoke(string $model)
    {
        try {
            $filters = $this->filterService->filterKeysByModel($model);
            return api()->ok(null, compact('model', 'filters'));
        } catch (\Throwable $e) {
            return api()->error((string) $e->getMessage());
        }
    }
}
