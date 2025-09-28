<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class ExamAttemptRepository extends BaseRepository
{
    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO exam_attempts (exam_id, user_id, started_at, status, timer_remaining) VALUES (:exam_id, :user_id, :started_at, :status, :timer_remaining)');
        $stmt->execute([
            'exam_id' => $data['exam_id'],
            'user_id' => $data['user_id'],
            'started_at' => $data['started_at'],
            'status' => $data['status'] ?? 'in_progress',
            'timer_remaining' => $data['timer_remaining'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function findActiveForUser(int $examId, int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM exam_attempts WHERE exam_id = :exam_id AND user_id = :user_id AND status = "in_progress" ORDER BY started_at DESC LIMIT 1');
        $stmt->execute([
            'exam_id' => $examId,
            'user_id' => $userId,
        ]);
        $attempt = $stmt->fetch(PDO::FETCH_ASSOC);
        return $attempt ?: null;
    }

    public function complete(int $attemptId, float $score, string $completedAt): void
    {
        $stmt = $this->db->prepare('UPDATE exam_attempts SET status = "completed", score = :score, completed_at = :completed_at WHERE id = :id');
        $stmt->execute([
            'id' => $attemptId,
            'score' => $score,
            'completed_at' => $completedAt,
        ]);
    }

    public function updateTimer(int $attemptId, int $remaining): void
    {
        $stmt = $this->db->prepare('UPDATE exam_attempts SET timer_remaining = :remaining WHERE id = :id');
        $stmt->execute([
            'id' => $attemptId,
            'remaining' => $remaining,
        ]);
    }
}
