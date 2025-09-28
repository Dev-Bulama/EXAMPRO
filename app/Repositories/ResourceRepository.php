<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class ResourceRepository extends BaseRepository
{
    public function forExam(int $examId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM resources WHERE exam_id = :exam_id');
        $stmt->execute(['exam_id' => $examId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO resources (exam_id, title, type, url, path) VALUES (:exam_id, :title, :type, :url, :path)');
        $stmt->execute([
            'exam_id' => $data['exam_id'],
            'title' => $data['title'],
            'type' => $data['type'],
            'url' => $data['url'] ?? null,
            'path' => $data['path'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM resources WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
