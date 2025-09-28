<?php

declare(strict_types=1);

namespace App\Services;

use App\Support\Database;
use DateInterval;
use DateTimeImmutable;
use PDO;

final class SessionService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function create(int $userId, string $ipAddress = '', string $userAgent = ''): string
    {
        $token = bin2hex(random_bytes(32));
        $expires = (new DateTimeImmutable())->add(new DateInterval('PT2H'));

        $stmt = $this->db->prepare('INSERT INTO sessions (user_id, token, ip_address, user_agent, expires_at) VALUES (:user_id, :token, :ip_address, :user_agent, :expires_at)');
        $stmt->execute([
            'user_id' => $userId,
            'token' => $token,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'expires_at' => $expires->format('Y-m-d H:i:s'),
        ]);
        $this->enforceSingleSession($userId, $token);
        return $token;
    }

    public function validateToken(string $token): ?int
    {
        $stmt = $this->db->prepare('SELECT user_id, expires_at FROM sessions WHERE token = :token');
        $stmt->execute(['token' => $token]);
        $session = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$session) {
            return null;
        }
        if (new DateTimeImmutable($session['expires_at']) < new DateTimeImmutable()) {
            $this->revokeToken($token);
            return null;
        }
        return (int) $session['user_id'];
    }

    public function revokeToken(string $token): void
    {
        $stmt = $this->db->prepare('DELETE FROM sessions WHERE token = :token');
        $stmt->execute(['token' => $token]);
    }

    private function enforceSingleSession(int $userId, string $activeToken): void
    {
        $stmt = $this->db->prepare('DELETE FROM sessions WHERE user_id = :user_id AND token != :token');
        $stmt->execute([
            'user_id' => $userId,
            'token' => $activeToken,
        ]);
    }
}
