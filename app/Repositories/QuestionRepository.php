<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class QuestionRepository extends BaseRepository
{
    public function forSection(int $sectionId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM questions WHERE section_id = :section_id ORDER BY order_index');
        $stmt->execute(['section_id' => $sectionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function options(int $questionId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM answer_options WHERE question_id = :question_id ORDER BY order_index');
        $stmt->execute(['question_id' => $questionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM questions WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO questions (section_id, type, prompt, media_path, metadata, points, order_index) VALUES (:section_id, :type, :prompt, :media_path, :metadata, :points, :order_index)');
        $stmt->execute([
            'section_id' => $data['section_id'],
            'type' => $data['type'],
            'prompt' => $data['prompt'],
            'media_path' => $data['media_path'] ?? null,
            'metadata' => isset($data['metadata']) ? json_encode($data['metadata']) : null,
            'points' => $data['points'] ?? 1,
            'order_index' => $data['order_index'] ?? 0,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare('UPDATE questions SET type = :type, prompt = :prompt, media_path = :media_path, metadata = :metadata, points = :points, order_index = :order_index WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'type' => $data['type'],
            'prompt' => $data['prompt'],
            'media_path' => $data['media_path'] ?? null,
            'metadata' => isset($data['metadata']) ? json_encode($data['metadata']) : null,
            'points' => $data['points'] ?? 1,
            'order_index' => $data['order_index'] ?? 0,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM questions WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
