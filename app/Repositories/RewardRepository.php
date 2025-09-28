<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class RewardRepository extends BaseRepository
{
    public function award(int $userId, int $points, string $reason): void
    {
        $stmt = $this->db->prepare('INSERT INTO rewards (user_id, points, reason) VALUES (:user_id, :points, :reason)');
        $stmt->execute([
            'user_id' => $userId,
            'points' => $points,
            'reason' => $reason,
        ]);
    }

    public function totalForUser(int $userId): int
    {
        $stmt = $this->db->prepare('SELECT COALESCE(SUM(points), 0) FROM rewards WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        return (int) $stmt->fetchColumn();
    }
}
