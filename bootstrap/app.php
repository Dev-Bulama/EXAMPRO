<?php

declare(strict_types=1);

use App\Support\Config;
use App\Support\Environment;
use App\Support\Logger;

define('BASE_PATH', dirname(__DIR__));

autoload([
    'App\\' => BASE_PATH . '/app',
    'Database\\' => BASE_PATH . '/database',
]);

Environment::load(BASE_PATH . '/.env');

Config::set('app', [
    'name' => 'ExamPro',
    'env' => Environment::get('APP_ENV', 'production'),
    'debug' => filter_var(Environment::get('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN),
    'key' => Environment::get('APP_KEY'),
    'url' => Environment::get('APP_URL', 'http://localhost'),
]);

Config::set('database', [
    'dsn' => sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        Environment::get('DB_HOST', '127.0.0.1'),
        Environment::get('DB_PORT', '3306'),
        Environment::get('DB_DATABASE', 'exampro')
    ),
    'username' => Environment::get('DB_USERNAME', 'root'),
    'password' => Environment::get('DB_PASSWORD', ''),
]);

Config::set('session', [
    'driver' => Environment::get('SESSION_DRIVER', 'file'),
    'path' => BASE_PATH . '/storage/sessions',
]);

Config::set('cache', [
    'driver' => Environment::get('CACHE_DRIVER', 'file'),
    'path' => BASE_PATH . '/storage/cache',
]);

Config::set('queue', [
    'connection' => Environment::get('QUEUE_CONNECTION', 'sync'),
]);

Config::set('paystack', [
    'secret' => Environment::get('PAYSTACK_SECRET'),
    'public' => Environment::get('PAYSTACK_PUBLIC'),
]);

Logger::init(BASE_PATH . '/storage/logs/app.log');

return [
    'config' => Config::class,
];

function autoload(array $namespaces): void
{
    spl_autoload_register(static function (string $class) use ($namespaces): void {
        foreach ($namespaces as $prefix => $basePath) {
            $len = strlen($prefix);
            if (strncmp($class, $prefix, $len) !== 0) {
                continue;
            }
            $relativeClass = substr($class, $len);
            $file = $basePath . '/' . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists($file)) {
                require $file;
            }
            return;
        }
    });
}
