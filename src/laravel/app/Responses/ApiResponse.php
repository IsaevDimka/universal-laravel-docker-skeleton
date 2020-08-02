<?php


namespace App\Responses;

use App\Contracts\ApiInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Traits\Macroable;

class ApiResponse implements ApiInterface
{
    use Macroable;

    const HTTP_OK                    = 200;
    const HTTP_BAD_REQUEST           = 400;
    const HTTP_FORBIDDEN             = 403;
    const HTTP_NOT_FOUND             = 404;
    const HTTP_METHOD_NOT_ALLOWED    = 405;
    const HTTP_UNPROCESSABLE_ENTITY  = 422; // RFC4918
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    const HTTP_SERVICE_UNAVAILABLE   = 503;

    const FORMAT_DATETIME = 'Y-m-d H:i:s';

    const BYPASS_KEY = 'debug';

    protected array $headers = [
        'Content-Type' => 'application/json',
    ];

    /**
     * Create API response.
     *
     * @param int    $status
     * @param string $message
     * @param array  $data
     * @param array  $extraData
     *
     * @return JsonResponse
     */
    public function response($status = 200, $message = null, $data = [], ...$extraData)
    {
        $json = [
            config('api.keys.status')  => config('api.stringify') ? strval($status) : $status,
            config('api.keys.message') => $message,
            config('api.keys.data')    => $data,
        ];

        if(is_countable($data) && config('api.include_data_count', false) && !empty($data)) {
            $json = array_merge($json, [config('api.keys.data_count') => config('api.stringify') ? strval(count($data)) : count($data)]);
        }

        if($extraData) {
            foreach($extraData as $extra){
                $json = array_merge($json, $extra);
            }
        }

        if(config('api.include_app_info', false)) {
            $version = (new \PragmaRX\Version\Package\Version());
            $app     = [
                'environment'    => app()->environment(),
                'locale'         => app()->getLocale(),
                'version'        => $version->format('compact'),
                'latest_release' => Carbon::create($version->format('timestamp-datetime'))
                                          ->toDateTimeString(),
            ];
            $json    = array_merge($json, $app);
        }

        if(config('app.debug')) {
            $debug = [
                'duration' => formatDuration((microtime(true) - LARAVEL_START)),
            ];
            $json  = array_merge($json, compact('debug'));
        }

        return (config('api.match_status')) ? response()
            ->json($json, $status)
            ->withHeaders($this->headers) : response()
            ->json($json)
            ->withHeaders($this->headers);
    }

    /**
     * Create successful (200) API response.
     *
     * @param string $message
     * @param array  $data
     * @param array  $extraData
     *
     * @return JsonResponse
     */
    public function ok($message = null, $data = [], ...$extraData)
    {
        if(is_null($message)) {
            $message = config('api.messages.success');
        }

        return $this->response(static::HTTP_OK, $message, $data, ...$extraData);
    }

    /**
     * Create successful (200) API response.
     *
     * @param string $message
     * @param array  $data
     * @param array  $extraData
     *
     * @return JsonResponse
     */
    public function success($message = null, $data = [], ...$extraData)
    {
        return $this->ok($message, $data, ...$extraData);
    }

    /**
     * Create Not found (404) API response.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function notFound($message = null)
    {
        if(is_null($message)) {
            $message = config('api.messages.notfound');
        }

        return $this->response(static::HTTP_NOT_FOUND, $message, []);
    }

    /**
     * Create Validation (422) API response.
     *
     * @param string $message
     * @param array  $errors
     * @param array  $extraData
     *
     * @return JsonResponse
     */
    public function validation($message = null, $errors = [], ...$extraData)
    {
        if(is_null($message)) {
            $message = config('api.messages.validation');
        }

        return $this->response(static::HTTP_UNPROCESSABLE_ENTITY, $message, $errors, ...$extraData);
    }

    /**
     * Create Validation (422) API response.
     *
     * @param string $message
     * @param array  $data
     * @param array  $extraData
     *
     * @return JsonResponse
     */
    public function forbidden($message = null, $data = [], ...$extraData)
    {
        if(is_null($message)) {
            $message = config('api.messages.forbidden');
        }

        return $this->response(static::HTTP_FORBIDDEN, $message, $data, ...$extraData);
    }

    /**
     * Create Server error (500) API response.
     *
     * @param string $message
     * @param array  $data
     * @param array  $extraData
     *
     * @return JsonResponse
     */
    public function error($message = null, $data = [], ...$extraData)
    {
        if(is_null($message)) {
            $message = config('api.messages.error');
        }

        return $this->response(static::HTTP_INTERNAL_SERVER_ERROR, $message, $data, ...$extraData);
    }
}
