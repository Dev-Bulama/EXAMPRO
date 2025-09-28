<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class QuizScheduleRepository extends BaseRepository
{
    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO quiz_schedules (exam_id, scheduled_for, duration_minutes) VALUES (:exam_id, :scheduled_for, :duration_minutes)');
        $stmt->execute([
            'exam_id' => $data['exam_id'],
            'scheduled_for' => $data['scheduled_for'],
            'duration_minutes' => $data['duration_minutes'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function upcoming(): array
    {
        $stmt = $this->db->query('SELECT quiz_schedules.*, exams.title FROM quiz_schedules JOIN exams ON exams.id = quiz_schedules.exam_id WHERE quiz_schedules.scheduled_for > NOW() ORDER BY quiz_schedules.scheduled_for');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
