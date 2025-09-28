<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\AnswerOptionRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ExamRepository;
use App\Repositories\ExamTypeRepository;
use App\Repositories\QuestionRepository;
use App\Support\Request;
use App\Support\Response;

final class AdminExamController
{
    public function __construct(
        private readonly ExamRepository $exams = new ExamRepository(),
        private readonly ExamTypeRepository $types = new ExamTypeRepository(),
        private readonly CategoryRepository $categories = new CategoryRepository(),
        private readonly QuestionRepository $questions = new QuestionRepository(),
        private readonly AnswerOptionRepository $options = new AnswerOptionRepository()
    ) {
    }

    public function dashboard(): Response
    {
        return Response::json([
            'data' => [
                'exams' => $this->exams->allActive(),
                'exam_types' => $this->types->all(),
                'categories' => $this->categories->all(),
            ],
        ]);
    }

    public function storeExam(Request $request): Response
    {
        $data = $request->all();
        $id = $this->exams->create($data);
        return Response::json(['message' => 'Exam created', 'data' => ['id' => $id]], 201);
    }

    public function updateExam(Request $request, array $params): Response
    {
        $this->exams->update((int) $params['id'], $request->all());
        return Response::json(['message' => 'Exam updated']);
    }

    public function deleteExam(Request $request, array $params): Response
    {
        $this->exams->delete((int) $params['id']);
        return Response::json(['message' => 'Exam deleted']);
    }

    public function addQuestion(Request $request, array $params): Response
    {
        $data = $request->all();
        $questionId = $this->questions->create([
            'section_id' => (int) $params['section_id'],
            'type' => $data['type'],
            'prompt' => $data['prompt'],
            'media_path' => $data['media_path'] ?? null,
            'metadata' => $data['metadata'] ?? null,
            'points' => $data['points'] ?? 1,
            'order_index' => $data['order_index'] ?? 0,
        ]);
        foreach ($data['options'] ?? [] as $option) {
            $this->options->create([
                'question_id' => $questionId,
                'label' => $option['label'] ?? null,
                'content' => $option['content'],
                'is_correct' => $option['is_correct'] ?? 0,
                'match_key' => $option['match_key'] ?? null,
                'order_index' => $option['order_index'] ?? 0,
            ]);
        }
        return Response::json(['message' => 'Question created', 'data' => ['id' => $questionId]], 201);
    }
}
