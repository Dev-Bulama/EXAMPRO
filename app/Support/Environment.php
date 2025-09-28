<?php

declare(strict_types=1);

namespace App\Support;

final class Environment
{
    private static array $values = [];

    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }
            [$key, $value] = array_pad(explode('=', $line, 2), 2, null);
            if ($key === null) {
                continue;
            }
            $key = trim($key);
            $value = $value !== null ? trim($value) : '';
            self::$values[$key] = $value;
            if (getenv($key) === false) {
                putenv(sprintf('%s=%s', $key, $value));
            }
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$values[$key] ?? getenv($key) ?? $default;
    }
}
