<?php

declare(strict_types=1);

namespace Lib;

use Lib\Exceptions\MethodNotAllowedHttpException;
use Lib\Exceptions\NotFoundHttpException;

class Route
{
    public const ROUTE_PREFIX = '/api/v1';

    private static array $routes = [];

    public static function add($expression, $function, $method = 'GET'): void
    {
        array_push(self::$routes, [
            'expression' => self::ROUTE_PREFIX . $expression,
            'function' => $function,
            'method' => $method,
        ]);
    }

    public static function run(string $basepath = '/')
    {
        // Parse current url
        $parsed_url = parse_url($_SERVER['REQUEST_URI']); //Parse Uri

        if (isset($parsed_url['path'])) {
            $path = $parsed_url['path'];
        } else {
            $path = '/';
        }

        // Get current request method
        $method = $_SERVER['REQUEST_METHOD'];

        $path_match_found = false;

        $route_match_found = false;

        foreach (self::$routes as $route) {

            // If the method matches check the path

            // Add basepath to matching string
            if ($basepath != '' && $basepath != '/') {
                $route['expression'] = '(' . $basepath . ')' . $route['expression'];
            }

            // Add 'find string start' automatically
            $route['expression'] = '^' . $route['expression'];

            // Add 'find string end' automatically
            $route['expression'] = $route['expression'] . '$';

            // Check path match
            if (preg_match('#' . $route['expression'] . '#', $path, $matches)) {
                $path_match_found = true;

                // Check method match
                if (strtolower($method) == strtolower($route['method'])) {
                    array_shift($matches); // Always remove first element. This contains the whole string

                    if ($basepath != '' && $basepath != '/') {
                        array_shift($matches); // Remove basepath
                    }

                    call_user_func_array($route['function'], $matches);

                    $route_match_found = true;

                    // Do not check other routes
                    break;
                }
            }
        }

        // No matching route was found
        if (! $route_match_found) {
            // But a matching path exists
            if ($path_match_found) {
                throw new MethodNotAllowedHttpException('405 Method Not Allowed', 405);
            }
            throw new NotFoundHttpException('404 Not Found', 404);
        }
    }
}
