<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class PrettyPrint
{
    /**
     * @var string the query parameter
     */
    public const QUERY_PARAMETER = 'pretty';

    /**
     * Apply pretty print if designated
     *
     * @param $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            if ((bool) $request->query(self::QUERY_PARAMETER)) {
                $response->setEncodingOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }
        return $response;
    }
}
