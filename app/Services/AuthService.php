<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use App\Support\Logger;

final class AuthService
{
    public function __construct(
        private readonly UserRepository $users = new UserRepository(),
        private readonly SessionService $sessions = new SessionService()
    ) {
    }

    public function register(array $data): array
    {
        $existing = $this->users->findByEmail($data['email']);
        if ($existing) {
            throw new \RuntimeException('Email already registered');
        }
        $userId = $this->users->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role' => $data['role'] ?? 'user',
            'language' => $data['language'] ?? 'en',
        ]);
        $token = $this->sessions->create($userId, $data['ip_address'] ?? '', $data['user_agent'] ?? '');
        Logger::info('User registered', ['user_id' => $userId]);
        return ['user_id' => $userId, 'token' => $token];
    }

    public function login(string $email, string $password, string $ipAddress = '', string $userAgent = ''): array
    {
        $user = $this->users->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            throw new \RuntimeException('Invalid credentials');
        }
        $token = $this->sessions->create((int) $user['id'], $ipAddress, $userAgent);
        Logger::info('User logged in', ['user_id' => $user['id']]);
        return ['token' => $token, 'user' => $user];
    }

    public function logout(string $token): void
    {
        $this->sessions->revokeToken($token);
    }
}
