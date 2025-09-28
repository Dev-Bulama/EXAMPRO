<?php

declare(strict_types=1);

require __DIR__ . '/../bootstrap/app.php';

use App\Support\Logger;
use App\Support\Request;
use App\Support\Response;

$request = Request::capture();

$router = require BASE_PATH . '/routes/api.php';

try {
    $response = $router->dispatch($request);
} catch (Throwable $exception) {
    Logger::error('Unhandled exception', ['message' => $exception->getMessage()]);
    $response = Response::json(['message' => 'Server Error'], 500);
}

$response->send();
