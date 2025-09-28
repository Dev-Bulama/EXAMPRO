<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class CategoryRepository extends BaseRepository
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM categories ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO categories (name, description) VALUES (:name, :description)');
        $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare('UPDATE categories SET name = :name, description = :description WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
