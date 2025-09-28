<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class AnswerOptionRepository extends BaseRepository
{
    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO answer_options (question_id, label, content, is_correct, match_key, order_index) VALUES (:question_id, :label, :content, :is_correct, :match_key, :order_index)');
        $stmt->execute([
            'question_id' => $data['question_id'],
            'label' => $data['label'] ?? null,
            'content' => $data['content'],
            'is_correct' => $data['is_correct'] ?? 0,
            'match_key' => $data['match_key'] ?? null,
            'order_index' => $data['order_index'] ?? 0,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function deleteByQuestion(int $questionId): void
    {
        $stmt = $this->db->prepare('DELETE FROM answer_options WHERE question_id = :question_id');
        $stmt->execute(['question_id' => $questionId]);
    }
}
