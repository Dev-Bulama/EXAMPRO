<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AnswerOptionRepository;
use App\Repositories\ExamAttemptRepository;
use App\Repositories\ExamRepository;
use App\Repositories\ExamResponseRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\RewardRepository;
use App\Repositories\ScoreboardRepository;
use DateTimeImmutable;

final class ExamService
{
    public function __construct(
        private readonly ExamRepository $exams = new ExamRepository(),
        private readonly QuestionRepository $questions = new QuestionRepository(),
        private readonly AnswerOptionRepository $options = new AnswerOptionRepository(),
        private readonly ExamAttemptRepository $attempts = new ExamAttemptRepository(),
        private readonly ExamResponseRepository $responses = new ExamResponseRepository(),
        private readonly ScoreboardRepository $scoreboard = new ScoreboardRepository(),
        private readonly RewardRepository $rewards = new RewardRepository()
    ) {
    }

    public function listExams(): array
    {
        $exams = $this->exams->allActive();
        foreach ($exams as &$exam) {
            $exam['sections'] = $this->exams->sections((int) $exam['id']);
        }
        return $exams;
    }

    public function examDetail(int $id): array
    {
        $exam = $this->exams->find($id);
        if (!$exam) {
            throw new \RuntimeException('Exam not found');
        }
        $exam['sections'] = $this->exams->sections($id);
        foreach ($exam['sections'] as &$section) {
            $section['questions'] = $this->questions->forSection((int) $section['id']);
            foreach ($section['questions'] as &$question) {
                $question['options'] = $this->questions->options((int) $question['id']);
            }
        }
        return $exam;
    }

    public function startExam(int $examId, int $userId): array
    {
        $exam = $this->exams->find($examId);
        if (!$exam || (int) $exam['is_active'] === 0) {
            throw new \RuntimeException('Exam not available');
        }
        $active = $this->attempts->findActiveForUser($examId, $userId);
        if ($active) {
            return $active;
        }
        $durationMinutes = (int) $exam['duration_minutes'];
        $attemptId = $this->attempts->create([
            'exam_id' => $examId,
            'user_id' => $userId,
            'started_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
            'timer_remaining' => $durationMinutes * 60,
        ]);
        return $this->attempts->findActiveForUser($examId, $userId) ?? ['id' => $attemptId];
    }

    public function submitAnswer(int $attemptId, int $questionId, mixed $answer): void
    {
        $question = $this->questions->find($questionId);
        if ($question === null) {
            throw new \RuntimeException('Question not found');
        }
        $options = $this->questions->options($questionId);
        $isCorrect = $this->evaluateAnswer($question, $options, $answer);
        $points = $isCorrect ? $this->getQuestionPoints($questionId) : 0.0;
        $this->responses->record([
            'attempt_id' => $attemptId,
            'question_id' => $questionId,
            'answer' => is_scalar($answer) ? (string) $answer : json_encode($answer),
            'is_correct' => $isCorrect ? 1 : 0,
            'points_awarded' => $points,
        ]);
    }

    public function finishAttempt(int $attemptId, int $examId, int $userId): float
    {
        $responses = $this->responses->forAttempt($attemptId);
        $score = array_reduce($responses, static fn(float $carry, array $response): float => $carry + (float) ($response['points_awarded'] ?? 0), 0.0);
        $this->attempts->complete($attemptId, $score, (new DateTimeImmutable())->format('Y-m-d H:i:s'));
        $this->scoreboard->updateScore($examId, $userId, $score, (new DateTimeImmutable())->format('Y-m-d H:i:s'));
        $exam = $this->exams->find($examId);
        if ($exam && (int) $exam['reward_points'] > 0) {
            $this->rewards->award($userId, (int) $exam['reward_points'], sprintf('Completed exam %s', $exam['title']));
        }
        return $score;
    }

    public function leaderboard(int $examId): array
    {
        return $this->scoreboard->leaderboard($examId);
    }

    private function getQuestionPoints(int $questionId): float
    {
        $question = $this->questions->find($questionId);
        return $question ? (float) $question['points'] : 0.0;
    }

    private function evaluateAnswer(array $question, array $options, mixed $answer): bool
    {
        $type = $question['type'];
        $metadata = $question['metadata'] ? json_decode((string) $question['metadata'], true) : [];
        $correctOptions = array_filter($options, static fn(array $option): bool => (int) $option['is_correct'] === 1);

        return match ($type) {
            'multiple_choice', 'ordering' => $this->compareOptions($correctOptions, $answer),
            'matching' => $this->compareMatching($correctOptions, (array) $answer),
            'fill_blank' => $this->compareFillBlank($correctOptions, $metadata, $answer),
            'short_answer' => $this->compareShortAnswer($metadata, (string) $answer),
            'comprehension', 'media' => $this->compareOptions($correctOptions, $answer),
            default => false,
        };
    }

    private function compareOptions(array $correctOptions, mixed $answer): bool
    {
        $expected = array_map(static fn(array $option): string => (string) ($option['label'] ?? $option['match_key'] ?? ''), $correctOptions);
        sort($expected);
        $actual = (array) $answer;
        sort($actual);
        return $expected === $actual;
    }

    private function compareMatching(array $correctOptions, array $answer): bool
    {
        $expected = [];
        foreach ($correctOptions as $option) {
            if (!isset($option['label'], $option['match_key'])) {
                continue;
            }
            $expected[$option['label']] = $option['match_key'];
        }
        ksort($expected);
        ksort($answer);
        return $expected === $answer;
    }

    private function compareFillBlank(array $correctOptions, array $metadata, mixed $answer): bool
    {
        $expected = array_map(static fn(array $option): string => strtolower(trim((string) $option['content'])), $correctOptions);
        if (empty($expected) && isset($metadata['answers'])) {
            $expected = array_map(static fn(string $value): string => strtolower(trim($value)), (array) $metadata['answers']);
        }
        $actual = array_map(static fn(string $value): string => strtolower(trim($value)), (array) $answer);
        sort($expected);
        sort($actual);
        return $expected === $actual;
    }

    private function compareShortAnswer(array $metadata, string $answer): bool
    {
        $expected = isset($metadata['answer']) ? strtolower(trim((string) $metadata['answer'])) : null;
        if ($expected === null) {
            return false;
        }
        return $expected === strtolower(trim($answer));
    }
}
