<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Support\Database;
use PDO;

abstract class BaseRepository
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }
}
