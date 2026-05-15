# CLAUDE.md

## Project Instruction

Before working on any task, always read `README.md` first.

The `README.md` contains the project architecture rules, folder structure, coding conventions, and implementation requirements.

Claude must follow the architecture and rules defined in `README.md` for every task.

## Required Workflow

For every task:

1. Read `README.md`.
2. Understand the current architecture rules.
3. Inspect the relevant files before editing.
4. Follow the modular clean architecture structure.
5. Do not create files outside the agreed structure unless necessary.
6. Do not overwrite existing files without checking their contents first.
7. Keep Controllers thin.
8. Put business logic inside Services.
9. Put database query logic inside Repositories.
10. Use DTOs to pass validated data from Controllers to Services.
11. Use Request classes for validation only.
12. Keep Models inside `app/Models`.
