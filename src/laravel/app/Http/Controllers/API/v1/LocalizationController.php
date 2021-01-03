<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;

class LocalizationController extends ApiController
{
    /**
     * @param string $locale
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(string $locale)
    {
        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }

        return api()->ok(null, compact('locale'));
    }

}
