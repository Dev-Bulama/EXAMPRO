<?php

declare(strict_types=1);

namespace App\Support;

use Closure;

final class Router
{
    private array $routes = [];

    public function add(string $method, string $path, Closure $action, array $middleware = []): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'action' => $action,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(Request $request): Response
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $request->method()) {
                continue;
            }
            $pattern = '#^' . preg_replace('#\{([^/]+)\}#', '(?P<$1>[^/]+)', $route['path']) . '$#';
            if (preg_match($pattern, $request->path(), $matches)) {
                $params = array_filter($matches, static fn($key) => !is_int($key), ARRAY_FILTER_USE_KEY);
                $action = $route['action'];
                $response = $this->executeMiddleware($route['middleware'], $request, $params, $action);
                return $response;
            }
        }
        return Response::json(['message' => 'Not Found'], 404);
    }

    private function executeMiddleware(array $middleware, Request $request, array $params, Closure $action): Response
    {
        $pipeline = array_reduce(
            array_reverse($middleware),
            static function ($next, $layer) {
                return static function (Request $request, array $params) use ($layer, $next) {
                    return $layer($request, $params, $next);
                };
            },
            static function (Request $request, array $params) use ($action) {
                return $action($request, $params);
            }
        );

        return $pipeline($request, $params);
    }
}
