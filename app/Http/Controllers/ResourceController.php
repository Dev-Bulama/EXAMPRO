<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\ResourceRepository;
use App\Support\Request;
use App\Support\Response;

final class ResourceController
{
    public function __construct(private readonly ResourceRepository $resources = new ResourceRepository())
    {
    }

    public function forExam(Request $request, array $params): Response
    {
        return Response::json(['data' => $this->resources->forExam((int) $params['exam_id'])]);
    }
}
