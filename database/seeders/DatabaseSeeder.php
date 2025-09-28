<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Support\Database;
use DateInterval;
use DateTimeImmutable;
use PDO;
use RuntimeException;

final class DatabaseSeeder
{
    public function run(): void
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();
        try {
            $password = password_hash('Admin@123', PASSWORD_BCRYPT);
            $pdo->exec("INSERT INTO users (name, email, password, role) VALUES ('Super Admin', 'admin@example.com', '$password', 'admin')");

            $pdo->exec("INSERT INTO categories (name, description) VALUES ('Cyber Security', 'Security certification prep'), ('Programming', 'Programming challenges')");
            $pdo->exec("INSERT INTO exam_types (name, description, duration_minutes) VALUES ('Practice Set', 'Self-paced practice', 45), ('Mock Exam', 'Simulated certification exam', 120)");

            $pdo->exec("INSERT INTO pricing_plans (name, description, price, duration_days) VALUES
                ('Starter', 'Access to practice sets', 9.99, 30),
                ('Professional', 'All mock exams and analytics', 29.99, 30),
                ('Enterprise', 'Team access with reporting', 99.99, 90)");

            $now = new DateTimeImmutable();
            $pdo->exec(sprintf(
                "INSERT INTO exams (title, description, exam_type_id, category_id, price, reward_points, starts_at, ends_at) VALUES
                ('CompTIA Security+ Practice', 'Timed practice with 50 questions', 1, 1, 19.99, 50, '%s', '%s'),
                ('Python Developer Mock', 'Scenario-based mock exam', 2, 2, 29.99, 75, '%s', '%s')",
                $now->format('Y-m-d H:i:s'),
                $now->add(new DateInterval('P30D'))->format('Y-m-d H:i:s'),
                $now->format('Y-m-d H:i:s'),
                $now->add(new DateInterval('P30D'))->format('Y-m-d H:i:s')
            ));

            $pdo->exec("INSERT INTO exam_sections (exam_id, title, instructions, order_index) VALUES
                (1, 'Core Concepts', 'Select the best answer for each question', 1),
                (1, 'Scenario Questions', 'Apply knowledge to scenarios', 2),
                (2, 'Language Fundamentals', 'Multiple choice questions', 1)");

            $pdo->exec("INSERT INTO questions (section_id, type, prompt, points, order_index) VALUES
                (1, 'multiple_choice', 'Which protocol provides secure remote access?', 2, 1),
                (1, 'multiple_choice', 'What is the primary purpose of a firewall?', 2, 2),
                (2, 'short_answer', 'Describe the principle of least privilege.', 4, 1),
                (3, 'multiple_choice', 'Which keyword defines a function in Python?', 1, 1)");

            $pdo->exec("INSERT INTO answer_options (question_id, label, content, is_correct, order_index) VALUES
                (1, 'A', 'SSH', 1, 1),
                (1, 'B', 'FTP', 0, 2),
                (1, 'C', 'Telnet', 0, 3),
                (1, 'D', 'SNMP', 0, 4),
                (2, 'A', 'Block unauthorized access', 1, 1),
                (2, 'B', 'Increase bandwidth', 0, 2),
                (2, 'C', 'Monitor CPU usage', 0, 3),
                (2, 'D', 'Provide encryption', 0, 4),
                (4, 'A', 'func', 0, 1),
                (4, 'B', 'function', 0, 2),
                (4, 'C', 'def', 1, 3),
                (4, 'D', 'lambda', 0, 4)");

            $pdo->exec("INSERT INTO resources (exam_id, title, type, url) VALUES
                (1, 'Security+ Study Guide', 'link', 'https://example.com/security-guide'),
                (2, 'Python Tips', 'link', 'https://example.com/python-tips')");

            $pdo->commit();
        } catch (RuntimeException|\Throwable $exception) {
            $pdo->rollBack();
            throw $exception;
        }
    }
}
