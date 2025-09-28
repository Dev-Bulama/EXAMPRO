<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class TransactionRepository extends BaseRepository
{
    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO transactions (user_id, plan_id, amount, status, reference, payment_method, metadata) VALUES (:user_id, :plan_id, :amount, :status, :reference, :payment_method, :metadata)');
        $stmt->execute([
            'user_id' => $data['user_id'],
            'plan_id' => $data['plan_id'],
            'amount' => $data['amount'],
            'status' => $data['status'] ?? 'pending',
            'reference' => $data['reference'],
            'payment_method' => $data['payment_method'],
            'metadata' => isset($data['metadata']) ? json_encode($data['metadata']) : null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare('UPDATE transactions SET status = :status WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'status' => $status,
        ]);
    }
}
