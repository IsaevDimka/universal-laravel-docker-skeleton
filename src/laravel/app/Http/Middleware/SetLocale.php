<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($locale = $this->parseLocale($request)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }

    protected function parseLocale(Request $request): ?string
    {
        $locale = $request->server('HTTP_ACCEPT_LANGUAGE');
        if (empty($locale)) {
            return null;
        }

        $locale = substr($locale, 0, strpos($locale, ',') ?: strlen($locale));

        $locales = config('app.locales');

        if (array_key_exists($locale, $locales)) {
            return $locale;
        }

        $locale = substr($locale, 0, 2);

        if (array_key_exists($locale, $locales)) {
            return $locale;
        }
    }
}
