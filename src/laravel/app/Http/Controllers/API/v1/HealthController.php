<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;
use App\Services\BackendService;

class HealthController extends ApiController
{
    protected BackendService $backendService;

    public function __construct(BackendService $backendService)
    {
        $this->backendService = $backendService;
    }

    public function __invoke()
    {
        $status = $this->backendService->getHealth();
        return api()->response(200, $status['message'], null, $status);
    }
}
