<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class NotificationRepository extends BaseRepository
{
    public function create(int $userId, string $title, string $body): void
    {
        $stmt = $this->db->prepare('INSERT INTO notifications (user_id, title, body) VALUES (:user_id, :title, :body)');
        $stmt->execute([
            'user_id' => $userId,
            'title' => $title,
            'body' => $body,
        ]);
    }

    public function unread(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM notifications WHERE user_id = :user_id AND is_read = 0 ORDER BY created_at DESC');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead(int $notificationId): void
    {
        $stmt = $this->db->prepare('UPDATE notifications SET is_read = 1 WHERE id = :id');
        $stmt->execute(['id' => $notificationId]);
    }
}
