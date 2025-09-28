<?php

declare(strict_types=1);

namespace App\Support;

use App\Repositories\UserRepository;
use App\Services\SessionService;

final class Auth
{
    public static function authenticate(Request $request, array $params, callable $next): Response
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return Response::json(['message' => 'Unauthorized'], 401);
        }

        $sessionService = new SessionService();
        $userId = $sessionService->validateToken($token);
        if ($userId === null) {
            return Response::json(['message' => 'Invalid token'], 401);
        }
        $userRepo = new UserRepository();
        $user = $userRepo->findById($userId);
        if ($user === null) {
            return Response::json(['message' => 'User not found'], 401);
        }
        $request->setAttribute('user', $user);
        return $next($request, $params);
    }

    public static function requireRole(string ...$roles): callable
    {
        return static function (Request $request, array $params, callable $next) use ($roles): Response {
            $user = $request->getAttribute('user');
            if ($user === null || !in_array($user['role'], $roles, true)) {
                return Response::json(['message' => 'Forbidden'], 403);
            }
            return $next($request, $params);
        };
    }
}
