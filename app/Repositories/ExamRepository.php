<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class ExamRepository extends BaseRepository
{
    public function allActive(): array
    {
        $stmt = $this->db->query('SELECT exams.*, exam_types.duration_minutes FROM exams JOIN exam_types ON exams.exam_type_id = exam_types.id WHERE exams.is_active = 1 ORDER BY exams.starts_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT exams.*, exam_types.duration_minutes FROM exams JOIN exam_types ON exams.exam_type_id = exam_types.id WHERE exams.id = :id');
        $stmt->execute(['id' => $id]);
        $exam = $stmt->fetch(PDO::FETCH_ASSOC);
        return $exam ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO exams (title, description, exam_type_id, category_id, is_active, price, reward_points, starts_at, ends_at) VALUES (:title, :description, :exam_type_id, :category_id, :is_active, :price, :reward_points, :starts_at, :ends_at)');
        $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'exam_type_id' => $data['exam_type_id'],
            'category_id' => $data['category_id'],
            'is_active' => $data['is_active'] ?? 1,
            'price' => $data['price'] ?? 0,
            'reward_points' => $data['reward_points'] ?? 0,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare('UPDATE exams SET title = :title, description = :description, exam_type_id = :exam_type_id, category_id = :category_id, is_active = :is_active, price = :price, reward_points = :reward_points, starts_at = :starts_at, ends_at = :ends_at WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'exam_type_id' => $data['exam_type_id'],
            'category_id' => $data['category_id'],
            'is_active' => $data['is_active'] ?? 1,
            'price' => $data['price'] ?? 0,
            'reward_points' => $data['reward_points'] ?? 0,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM exams WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function sections(int $examId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM exam_sections WHERE exam_id = :id ORDER BY order_index');
        $stmt->execute(['id' => $examId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
