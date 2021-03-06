<?php

declare(strict_types=1);

namespace Api;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;

class ApiResponse implements ApiInterface
{
    public const FORMAT_DATETIME = Carbon::DEFAULT_TO_STRING_FORMAT;

    public const BYPASS_KEY = 'debug';

    protected array $headers = [
        'Content-Type' => 'application/json',
    ];

    /**
     * Create API response.
     *
     * @param int   $status
     * @param string|null  $message
     * @param array $data
     * @param array $extraData
     *
     * @return JsonResponse
     */
    public function response($status = ResponseStatus::HTTP_OK, $message = null, $data = [], ...$extraData)
    {
        if (is_null($message) && config('apifacade.include_nullable_message_status_text')) {
            $message = ResponseStatus::$statusTexts[$status] ?? null;
        }

        $json = [
            config('apifacade.keys.status') => config('apifacade.stringify') ? strval($status) : $status,
            config('apifacade.keys.message') => $message,
            config('apifacade.keys.data') => $data,
        ];

        if (is_countable($data) && config('apifacade.include_data_count', false) && ! empty($data)) {
            $json = array_merge($json, [
                config('apifacade.keys.data_count') => config('apifacade.stringify') ? strval(count($data)) : count($data),
            ]);
        }

        if ($extraData) {
            foreach ($extraData as $extra) {
                $json = array_merge($json, $extra);
            }
        }

        if (config('apifacade.include_app_info', false)) {
            $version = (new \PragmaRX\Version\Package\Version());
            $app = [
                'environment' => app()->environment(),
                'locale' => app()->getLocale(),
                'version' => $version->format('compact'),
                'latest_release' => Carbon::create($version->format('timestamp-datetime'))->toDateTimeString(),
            ];
            $json = array_merge($json, $app);
        }

        if (config('apifacade.debug')) {
            $debug = [
                'duration' => format_duration((microtime(true) - LARAVEL_START)),
                //                'route' => \request()->route(),
            ];
            $json = array_merge($json, compact('debug'));
        }

        if (config('apifacade.notify_too_many_requests') && $status == ResponseStatus::HTTP_TOO_MANY_REQUESTS) {
            $request = \request();
            $ip = $request->ip();
            $ua = $request->userAgent();
            $token = $request->bearerToken();
            $method = $request->getMethod();
            $fullUrl = $request->fullUrl();
            $data = $request->toArray();
            $user_id = optional(auth())->id();
            $hit = compact('ip', 'ua', 'token', 'fullUrl', 'method', 'data', 'user_id');
            $json = array_merge($json, compact('hit'));
            logger()->channel('telegram')->warning($message, $json);
            logger()->warning($message, $json);
        }

        return (config('apifacade.match_status'))
            ? response()->json($json, $status, [])->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)->withHeaders($this->headers)
            : response()->json($json)->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)->withHeaders($this->headers);
    }

    /**
     * Create successful (200) API response.
     *
     * @param string|null $message
     * @param array       $data
     * @param array       $extraData
     *
     * @return JsonResponse
     */
    public function ok($message = null, $data = [], ...$extraData)
    {
        if (is_null($message)) {
            $message = config('apifacade.messages.success');
        }

        return $this->response(ResponseStatus::HTTP_OK, $message, $data, ...$extraData);
    }

    /**
     * Create successful (200) API response.
     *
     * @param string|null $message
     * @param array       $data
     * @param array       $extraData
     *
     * @return JsonResponse
     */
    public function success($message = null, $data = [], ...$extraData)
    {
        return $this->ok($message, $data, ...$extraData);
    }

    /**
     * Create bad (400) API response.
     *
     * @param string|null $message
     * @param array       $errors
     * @param array       $extraData
     *
     * @return JsonResponse
     */
    public function bad($message = null, $errors = [], ...$extraData)
    {
        if (is_null($message)) {
            $message = config('apifacade.messages.bad');
        }

        return $this->response(ResponseStatus::HTTP_BAD_REQUEST, $message, [], compact('errors'), ...$extraData);
    }

    /**
     * Create Not found (404) API response.
     *
     * @param string|null $message
     *
     * @return JsonResponse
     */
    public function notFound($message = null)
    {
        if (is_null($message)) {
            $message = config('apifacade.messages.notfound');
        }

        return $this->response(ResponseStatus::HTTP_NOT_FOUND, $message, []);
    }

    /**
     * Create Validation (422) API response.
     *
     * @param string|null $message
     * @param array       $errors
     * @param array       $extraData
     *
     * @return JsonResponse
     */
    public function validation($message = null, $errors = [], ...$extraData)
    {
        if (is_null($message)) {
            $message = config('apifacade.messages.validation');
        }

        return $this->response(ResponseStatus::HTTP_UNPROCESSABLE_ENTITY, $message, [], compact('errors'), ...$extraData);
    }

    /**
     * Create forbidden (403) API response.
     *
     * @param string|null $message
     * @param array       $errors
     * @param array       $extraData
     *
     * @return JsonResponse
     */
    public function forbidden($message = null, $errors = [], ...$extraData)
    {
        if (is_null($message)) {
            $message = config('apifacade.messages.forbidden');
        }

        return $this->response(ResponseStatus::HTTP_FORBIDDEN, $message, [], compact('errors'), ...$extraData);
    }

    /**
     * Create Server error (500) API response.
     *
     * @param string|null $message
     * @param array       $errors
     * @param array       $extraData
     *
     * @return JsonResponse
     */
    public function error($message = null, $errors = [], ...$extraData)
    {
        if (is_null($message)) {
            $message = config('apifacade.messages.error');
        }

        return $this->response(ResponseStatus::HTTP_INTERNAL_SERVER_ERROR, $message, [], compact('errors'), ...$extraData);
    }
}
