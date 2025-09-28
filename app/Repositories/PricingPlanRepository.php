<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class PricingPlanRepository extends BaseRepository
{
    public function active(): array
    {
        $stmt = $this->db->query('SELECT * FROM pricing_plans WHERE is_active = 1 ORDER BY price');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO pricing_plans (name, description, price, duration_days, is_active) VALUES (:name, :description, :price, :duration_days, :is_active)');
        $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'duration_days' => $data['duration_days'],
            'is_active' => $data['is_active'] ?? 1,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare('UPDATE pricing_plans SET name = :name, description = :description, price = :price, duration_days = :duration_days, is_active = :is_active WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'duration_days' => $data['duration_days'],
            'is_active' => $data['is_active'] ?? 1,
        ]);
    }
}
