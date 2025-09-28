<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class ExamResponseRepository extends BaseRepository
{
    public function record(array $data): void
    {
        $stmt = $this->db->prepare('INSERT INTO exam_responses (attempt_id, question_id, answer, is_correct, points_awarded) VALUES (:attempt_id, :question_id, :answer, :is_correct, :points_awarded) ON DUPLICATE KEY UPDATE answer = VALUES(answer), is_correct = VALUES(is_correct), points_awarded = VALUES(points_awarded), updated_at = CURRENT_TIMESTAMP');
        $stmt->execute([
            'attempt_id' => $data['attempt_id'],
            'question_id' => $data['question_id'],
            'answer' => $data['answer'],
            'is_correct' => $data['is_correct'],
            'points_awarded' => $data['points_awarded'],
        ]);
    }

    public function forAttempt(int $attemptId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM exam_responses WHERE attempt_id = :attempt_id');
        $stmt->execute(['attempt_id' => $attemptId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
