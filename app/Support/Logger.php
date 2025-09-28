<?php

declare(strict_types=1);

namespace App\Support;

use DateTimeImmutable;
use DateTimeZone;

final class Logger
{
    private static string $path;

    public static function init(string $path): void
    {
        self::$path = $path;
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
    }

    public static function info(string $message, array $context = []): void
    {
        self::write('INFO', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::write('ERROR', $message, $context);
    }

    private static function write(string $level, string $message, array $context): void
    {
        $timestamp = (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format(DateTimeImmutable::ATOM);
        $log = sprintf('[%s] %s: %s %s%s', $timestamp, $level, $message, json_encode($context, JSON_THROW_ON_ERROR), PHP_EOL);
        file_put_contents(self::$path, $log, FILE_APPEND);
    }
}
