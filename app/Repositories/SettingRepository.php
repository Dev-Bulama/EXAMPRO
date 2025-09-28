<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class SettingRepository extends BaseRepository
{
    public function set(string $name, string $value): void
    {
        $stmt = $this->db->prepare('INSERT INTO settings (name, value) VALUES (:name, :value) ON DUPLICATE KEY UPDATE value = VALUES(value), updated_at = CURRENT_TIMESTAMP');
        $stmt->execute([
            'name' => $name,
            'value' => $value,
        ]);
    }

    public function get(string $name, ?string $default = null): ?string
    {
        $stmt = $this->db->prepare('SELECT value FROM settings WHERE name = :name');
        $stmt->execute(['name' => $name]);
        $value = $stmt->fetchColumn();
        return $value !== false ? (string) $value : $default;
    }
}
