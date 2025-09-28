<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Support\Request;
use App\Support\Response;

final class AdminUserController
{
    public function __construct(private readonly UserRepository $users = new UserRepository())
    {
    }

    public function index(): Response
    {
        return Response::json(['data' => $this->users->all()]);
    }

    public function update(Request $request, array $params): Response
    {
        $data = $request->all();
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        $this->users->update((int) $params['id'], $data);
        return Response::json(['message' => 'User updated']);
    }

    public function destroy(Request $request, array $params): Response
    {
        $this->users->delete((int) $params['id']);
        return Response::json(['message' => 'User deleted']);
    }
}
