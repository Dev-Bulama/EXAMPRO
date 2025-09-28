# ExamPro Platform Roadmap

This repository tracks the planning and bootstrapping utilities for the ExamPro Online Exam Management System. The current deliverables focus on the initial scaffolding script and implementation roadmap.

## Getting Started
1. Review the [implementation plan](docs/implementation-plan.md) to understand the delivery milestones.
2. Run the scaffolding script to bootstrap a Laravel codebase:
   ```bash
   ./scripts/scaffold_laravel.sh exampro-app /path/to/projects
   ```
3. Follow the roadmap to incrementally ship the Admin and User modules.

## Repository Structure
- `scripts/` – automation utilities (Laravel scaffolding, forthcoming CI helpers).
- `docs/` – design decisions, milestones, and onboarding documentation.
- `index.html` – placeholder landing page until the Laravel frontend is introduced.

## Next Steps
- Implement Admin authentication and dashboard using Laravel Breeze.
- Model the database entities outlined in the plan.
- Configure TailwindCSS (CDN first, then build pipeline) for consistent styling.
