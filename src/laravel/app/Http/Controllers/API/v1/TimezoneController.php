<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;
use App\Http\Resources\TimezoneResource;
use App\Models\Timezone;

class TimezoneController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return api()->ok(null, TimezoneResource::collection(Timezone::all()));
    }
}
