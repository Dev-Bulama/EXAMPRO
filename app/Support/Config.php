<?php

declare(strict_types=1);

namespace App\Support;

final class Config
{
    private static array $store = [];

    public static function set(string $key, array $config): void
    {
        self::$store[$key] = $config;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $value = self::$store;
        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }
        return $value;
    }
}
