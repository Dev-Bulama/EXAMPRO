<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\ResourceRepository;
use App\Support\Request;
use App\Support\Response;

final class AdminResourceController
{
    public function __construct(private readonly ResourceRepository $resources = new ResourceRepository())
    {
    }

    public function store(Request $request): Response
    {
        $id = $this->resources->create($request->all());
        return Response::json(['message' => 'Resource added', 'data' => ['id' => $id]], 201);
    }

    public function destroy(Request $request, array $params): Response
    {
        $this->resources->delete((int) $params['id']);
        return Response::json(['message' => 'Resource removed']);
    }
}
