<?php

declare(strict_types=1);

require __DIR__ . '/../bootstrap/app.php';

use Database\Seeders\DatabaseSeeder;

$seeder = new DatabaseSeeder();
$seeder->run();

echo "Database seeded successfully" . PHP_EOL;
