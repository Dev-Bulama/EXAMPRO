<?php

declare(strict_types=1);

namespace App\Repositories;

use DateInterval;
use DateTimeImmutable;
use PDO;

final class SubscriptionRepository extends BaseRepository
{
    public function create(int $userId, int $planId, int $durationDays): void
    {
        $start = new DateTimeImmutable();
        $end = $start->add(new DateInterval(sprintf('P%dD', $durationDays)));

        $stmt = $this->db->prepare('INSERT INTO subscriptions (user_id, plan_id, started_at, expires_at) VALUES (:user_id, :plan_id, :started_at, :expires_at)');
        $stmt->execute([
            'user_id' => $userId,
            'plan_id' => $planId,
            'started_at' => $start->format('Y-m-d H:i:s'),
            'expires_at' => $end->format('Y-m-d H:i:s'),
        ]);
    }

    public function activeForUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT subscriptions.*, pricing_plans.name FROM subscriptions JOIN pricing_plans ON pricing_plans.id = subscriptions.plan_id WHERE subscriptions.user_id = :user_id AND subscriptions.expires_at > NOW()');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
