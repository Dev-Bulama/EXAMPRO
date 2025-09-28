<?php

declare(strict_types=1);

namespace App\Support;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection === null) {
            $config = Config::get('database');
            try {
                self::$connection = new PDO(
                    $config['dsn'],
                    $config['username'],
                    $config['password'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $exception) {
                Logger::error('Database connection failed', ['error' => $exception->getMessage()]);
                throw $exception;
            }
        }
        return self::$connection;
    }
}
