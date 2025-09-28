<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ExamService;
use App\Support\Request;
use App\Support\Response;

final class ExamController
{
    public function __construct(private readonly ExamService $exams = new ExamService())
    {
    }

    public function index(): Response
    {
        return Response::json(['data' => $this->exams->listExams()]);
    }

    public function show(Request $request, array $params): Response
    {
        return Response::json(['data' => $this->exams->examDetail((int) $params['id'])]);
    }

    public function start(Request $request, array $params): Response
    {
        $user = $request->getAttribute('user');
        $attempt = $this->exams->startExam((int) $params['id'], (int) $user['id']);
        return Response::json(['message' => 'Exam started', 'data' => $attempt]);
    }

    public function submit(Request $request, array $params): Response
    {
        $data = $request->all();
        $this->exams->submitAnswer((int) $params['attempt_id'], (int) $data['question_id'], $data['answer']);
        return Response::json(['message' => 'Answer recorded']);
    }

    public function finish(Request $request, array $params): Response
    {
        $user = $request->getAttribute('user');
        $score = $this->exams->finishAttempt((int) $params['attempt_id'], (int) $params['exam_id'], (int) $user['id']);
        return Response::json(['message' => 'Exam completed', 'data' => ['score' => $score]]);
    }

    public function leaderboard(Request $request, array $params): Response
    {
        return Response::json(['data' => $this->exams->leaderboard((int) $params['exam_id'])]);
    }
}
