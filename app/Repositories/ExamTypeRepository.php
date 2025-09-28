<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class ExamTypeRepository extends BaseRepository
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM exam_types ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO exam_types (name, description, duration_minutes) VALUES (:name, :description, :duration)');
        $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'duration' => $data['duration_minutes'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare('UPDATE exam_types SET name = :name, description = :description, duration_minutes = :duration WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'duration' => $data['duration_minutes'],
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM exam_types WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
