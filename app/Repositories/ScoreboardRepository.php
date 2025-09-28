<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class ScoreboardRepository extends BaseRepository
{
    public function updateScore(int $examId, int $userId, float $score, string $attemptedAt): void
    {
        $stmt = $this->db->prepare('INSERT INTO scoreboard (exam_id, user_id, best_score, attempts, last_attempt_at) VALUES (:exam_id, :user_id, :best_score, 1, :attempted_at) ON DUPLICATE KEY UPDATE best_score = GREATEST(best_score, VALUES(best_score)), attempts = attempts + 1, last_attempt_at = VALUES(last_attempt_at)');
        $stmt->execute([
            'exam_id' => $examId,
            'user_id' => $userId,
            'best_score' => $score,
            'attempted_at' => $attemptedAt,
        ]);
    }

    public function leaderboard(int $examId): array
    {
        $stmt = $this->db->prepare('SELECT users.name, scoreboard.best_score, scoreboard.attempts, scoreboard.last_attempt_at FROM scoreboard JOIN users ON users.id = scoreboard.user_id WHERE scoreboard.exam_id = :exam_id ORDER BY scoreboard.best_score DESC, scoreboard.last_attempt_at ASC');
        $stmt->execute(['exam_id' => $examId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
