# ExamPro Deployment Blueprint

## 1. Application Stack
- Native PHP 8.2 runtime with custom lightweight framework (router, request/response, repository layer).
- MySQL 8.x relational database for transactional storage.
- TailwindCSS CDN for marketing pages and email-friendly templates.
- Optional Redis/Memcached upgrade path for cache/session drivers.

## 2. Domain Overview
| Area | Components | Highlights |
| --- | --- | --- |
| Identity & Access | Users, Sessions | Bcrypt passwords, single active session enforcement, role-based middleware. |
| Exams & Content | Exams, ExamTypes, Categories, Sections, Questions, AnswerOptions, Resources | Supports seven question types, media metadata, ordered sections, and resource attachments. |
| Attempts & Analytics | ExamAttempts, ExamResponses, Scoreboard, Rewards | Timer tracking, score aggregation, leaderboard, and reward ledger integration. |
| Commerce | PricingPlans, Subscriptions, Transactions | Paystack-ready transactions, manual bank approvals, plan assignment automation. |
| Configuration | Settings, Notifications, QuizSchedules | Landing page controls, notification delivery, live quiz scheduling. |

## 3. Database Roadmap
1. Run `database/migrations/001_create_tables.sql` to create all schemas with foreign keys and unique indexes.
2. Execute `Database\Seeders\DatabaseSeeder` after bootstrap to populate:
   - Super admin account (`admin@example.com` / `Admin@123`).
   - Sample categories, exam types, pricing plans, exams, questions, and resources.
3. Add environment-specific seeders for localisation strings or region-based pricing.

## 4. Admin Module Delivery
- **Authentication & Sessions**: Token-based authentication, revoke/issue endpoints, session audit logging via `sessions` table.
- **Exam Management**: CRUD endpoints for exams, sections, questions, answer options, and multimedia metadata.
- **Content & Settings**: Landing page text via settings table, resource management with file/link metadata.
- **Plans & Payments**: Create/update plans, assign users, manage transactions, approve manual transfers.
- **User Governance**: Update/delete users, control roles, view active subscriptions and reward tallies.

## 5. Learner Experience
- **Discovery**: `/api/exams` exposes catalog with categories, pricing, and active window.
- **Exam Runner**: Start endpoints return attempt tokens, timers tracked server-side, answer submission locked to single direction.
- **Progress Center**: Dashboard endpoint aggregates subscriptions, rewards, and unread notifications.
- **Resources**: Protected resources endpoint returns study materials tied to exams/plans.
- **Leaderboard**: Real-time leaderboard fetch for motivation and cohort benchmarking.

## 6. Monetisation Workflow
1. Learner selects plan; client calls `/api/payments/initiate` for Paystack reference.
2. Paystack callback (or manual bank transfer) validated by admin via `/api/payments/{transaction}/approve`.
3. Admin assigns plan duration to user through `/api/admin/plans/assign` when payment is successful.

## 7. Live Quiz Scheduling
- `quiz_schedules` table enables admins to create live events with start time and duration.
- Participants tracked via `quiz_participants` for invitation status and attendance.
- Real-time delivery can be layered using WebSockets (outside current scope) referencing these tables.

## 8. Internationalisation & Accessibility
- Store preferred language in users table; localise copy through settings table or translation JSON files.
- Tailwind layout uses high-contrast palette and semantic HTML for screen reader compatibility.
- RTL support achievable via Tailwind RTL plugin or alternate stylesheet drop-in.

## 9. DevOps & Quality
- Configure `.env` per environment, rotate `APP_KEY`, and secure Paystack credentials.
- Add PHPUnit feature tests targeting authentication, exam flow, and admin CRUD.
- Set up CI (GitHub Actions) to run tests and static analysis (PHPStan, PHP-CS-Fixer) before deployments.
- Harden production with Nginx/PHP-FPM, HTTPS, automated backups, and log shipping.

## 10. Next Enhancements
- Integrate WebSocket gateway for live quiz countdowns and invigilator messaging.
- Implement PDF report generation for attempts and analytics exports.
- Add SCORM/xAPI ingestion for external learning modules.
- Build React/Vue SPA client on top of the existing RESTful API.
