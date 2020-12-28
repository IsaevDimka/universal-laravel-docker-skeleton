<?php

if (!function_exists('api')) {
    /**
     * Create a new APIResponse instance.
     *
     * @param int         $status
     * @param string|null $message
     * @param array       $data
     * @param array       $extraData
     *
     * @return \Api\ApiInterface|\Illuminate\Http\JsonResponse
     */
    function api($status = 200, $message = null, $data = [], ...$extraData)
    {
        if (func_num_args() === 0) {
            return app(Api\ApiInterface::class);
        }

        return app(Api\ApiInterface::class)->response($status, $message, $data, ...$extraData);
    }
}