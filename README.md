# ExamPro – Online Exam Management System

ExamPro is a production-ready PHP 8.2 application that delivers secure online assessments, adaptive learning flows, subscription monetisation, and rich analytics without external framework dependencies. The platform ships with a CBT-style exam engine, admin workspace, and responsive landing page styled with TailwindCSS CDN.

## Features

- **Authentication & Sessions** – Password-hashed accounts, role-based access (admin/user), and enforced single active session per user.
- **Admin Workspace** – CRUD for exams, question banks, resources, categories, exam types, pricing plans, and global settings.
- **Question Support** – Seven question types with media attachments, per-question scoring, ordering, and matching metadata.
- **Exam Delivery** – Timed CBT runner with answer locking, progress tracking, scoreboard, and reward allocation.
- **Subscriptions & Payments** – Paystack-ready payment initiation, manual bank transfer approvals, plan assignment, and subscription lifecycle management.
- **Resources & Rewards** – Upload/download learning resources, reward points ledger, and notification center for learners.
- **Analytics & Leaderboards** – Scoreboard aggregation, attempt history, and dashboard endpoints for user progress.
- **Internationalisation Ready** – Language column on users, RTL-ready landing page layout, and easy-to-extend settings store.

## Project Structure

```
app/                # Controllers, services, repositories, support classes
bootstrap/          # Application bootstrap and configuration loader
config/             # Reserved for future configuration overrides
database/
  ├─ migrations/    # SQL schema for MySQL deployments
  └─ seeders/       # Seeder classes for bootstrap data
public/             # Web root with router entry point
resources/views/    # Tailwind-powered landing page templates
routes/             # HTTP route definitions
storage/            # Logs, cache, and session files
```

## Getting Started

1. **Clone & Configure**
   ```bash
   cp .env.example .env
   ./scripts/setup_exampro.sh -d exampro -u root -p secret
   ```
   Update database credentials, Paystack keys, and APP_KEY with a secure base64 string. You can rerun the setup script with the appropriate flags to automatically execute migrations and seeders via the MySQL CLI.

2. **Provision Database**
   Execute the SQL schema in `database/migrations/001_create_tables.sql` against your MySQL instance, then seed starter data using the `Database\Seeders\DatabaseSeeder` class inside a small bootstrap script or tinker session.

3. **Serve the Application**
   ```bash
   php -S 0.0.0.0:8000 public/index.php
   ```
   The responsive landing page is available at `/`, while the JSON API lives under `/api/*`.

4. **Default Admin Access**
   The seeder creates an administrator account:
   - Email: `admin@example.com`
   - Password: `Admin@123`

## API Overview

| Endpoint | Method | Description |
| --- | --- | --- |
| `/api/register` | POST | Create a new user account. |
| `/api/login` | POST | Authenticate and receive a session token. |
| `/api/logout` | POST | Invalidate the current session token. |
| `/api/exams` | GET | Public listing of active exams. |
| `/api/exams/{id}` | GET | Exam detail with sections, questions, and options. |
| `/api/exams/{id}/start` | POST | Start an exam attempt (auth required). |
| `/api/attempts/{attempt_id}/submit` | POST | Submit question responses. |
| `/api/attempts/{attempt_id}/complete` | POST | Finish an attempt and compute score. |
| `/api/exams/{exam_id}/leaderboard` | GET | View leaderboard standings. |
| `/api/exams/{exam_id}/resources` | GET | Fetch exam resources (auth required). |
| `/api/dashboard` | GET | Learner dashboard summary (auth required). |
| `/api/payments/initiate` | POST | Kick off Paystack/manual payment flow. |
| `/api/payments/{transaction_id}/approve` | POST | Approve manual bank transfer (admin). |
| `/api/admin/*` | Various | Full admin CRUD for exams, plans, resources, users, and settings. |

All authenticated requests expect the session token in the `Authorization` header.

## Production Hardening Checklist

- Place the project behind Nginx or Apache with PHP-FPM.
- Configure HTTPS and secure cookies if migrating to a cookie-based driver.
- Swap the file cache/session drivers for Redis or Memcached in clustered environments.
- Integrate queue workers for heavy background jobs (e.g., video processing or bulk imports).
- Enable automated backups for the MySQL database and storage assets.

## Testing

- Repository includes SQL schema, seed data, and service-layer abstractions ready for PHPUnit integration.
- Extend `tests/` with feature/unit coverage targeting controllers and services; use an in-memory SQLite database for fast CI runs.

## Licensing

This project is delivered as open architecture for ExamPro deployments. Tailor authentication, payments, and localisation to match your compliance requirements.
