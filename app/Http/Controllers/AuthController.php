<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Support\Request;
use App\Support\Response;

final class AuthController
{
    public function __construct(private readonly AuthService $auth = new AuthService())
    {
    }

    public function register(Request $request): Response
    {
        $data = $request->all();
        $result = $this->auth->register([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'] ?? 'user',
            'language' => $data['language'] ?? 'en',
            'ip_address' => $request->header('X-Forwarded-For', ''),
            'user_agent' => $request->header('User-Agent', ''),
        ]);
        return Response::json(['message' => 'Registration successful', 'data' => $result], 201);
    }

    public function login(Request $request): Response
    {
        $data = $request->all();
        $result = $this->auth->login(
            $data['email'],
            $data['password'],
            $request->header('X-Forwarded-For', ''),
            $request->header('User-Agent', '')
        );
        return Response::json(['message' => 'Login successful', 'data' => $result]);
    }

    public function logout(Request $request): Response
    {
        $token = $request->header('Authorization', '');
        if ($token !== '') {
            $this->auth->logout($token);
        }
        return Response::json(['message' => 'Logged out']);
    }
}
