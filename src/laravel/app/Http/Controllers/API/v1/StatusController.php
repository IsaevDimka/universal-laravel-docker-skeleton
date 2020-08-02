<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;
use App\Services\BackendService;

class StatusController extends ApiController
{
    protected BackendService $backendService;

    public function __construct(BackendService $backendService)
    {
        $this->backendService = $backendService;
    }

    public function __invoke()
    {
        $status = $this->backendService->getStatus();
        return api()->response($status['status'], $status['message'], null, $status);
    }
}
