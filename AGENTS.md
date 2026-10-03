# AndUs Development Rules

## Project Stack

This is a Laravel/TALL-stack project.

Prefer the existing project stack and tooling:

- PHP
- Laravel
- Livewire
- Blade
- Volt, where already used
- Alpine.js
- Tailwind CSS
- Vite
- Artisan
- Pest / PHPUnit
- Laravel Pint
- Composer
- Standard Git and shell commands

Do not introduce or use another programming language, codebase, framework, scripting language, or external tooling for routine implementation work when the task can be completed with the existing Laravel/TALL stack.

Examples include, but are not limited to:

- Python
- Ruby
- Go
- Perl
- ad hoc Node.js scripts
- external code-generation scripts
- unrelated frameworks or libraries

If using something outside the existing project stack is genuinely necessary, explain why first and ask for approval before using it.

## File Editing Rules

For small or scoped changes:

- Prefer small, targeted edits.
- Preserve existing content, structure, formatting, and behavior.
- Do not rewrite an entire file when only a few lines need to change.
- Do not use Python heredocs, Python scripts, Node scripts, or other generated scripts to rewrite source files.
- Prefer `apply_patch` or another narrowly scoped editing method when appropriate.
- Do not perform broad automated rewrites across multiple files unless explicitly approved.
- Show or summarize each targeted change clearly.

## Scope Control

Stay strictly within the requested task, audit phase, and audit step.

Do not:

- refactor unrelated code
- modify unrelated files
- change unrelated configuration
- introduce new dependencies unless necessary and approved
- change production settings unless explicitly requested
- move into later audit phases without instruction
- commit, push, deploy, or create branches unless explicitly requested

Investigate first, then make the smallest safe change.

## Laravel/TALL Preference

When solving a problem, prefer Laravel-native and TALL-native approaches first.

Examples:

- Laravel configuration
- service providers
- Eloquent
- migrations
- seeders
- factories
- validation
- mailables
- queues
- middleware
- rate limiting
- Livewire components
- Blade / Volt
- Alpine.js
- Tailwind CSS
- Artisan commands
- Laravel testing tools

Do not replace a Laravel-native solution with a custom script or unrelated technology unless there is a clear technical reason.

## Verification

After PHP or Laravel changes, run the relevant checks when appropriate:

- focused tests
- `php artisan test`
- Laravel Pint on changed PHP files
- `git diff --check`

If frontend files change, also run the relevant existing frontend build or validation command.

Before considering the task complete:

- show the relevant diff
- summarize exactly what changed
- note any remaining production or deployment steps
- confirm that unrelated files were left unchanged

## Production Safety

Do not modify:

- the real `.env`
- Render settings
- production environment variables
- production databases
- deployed services

unless explicitly instructed.

When production changes are requested:

- limit the change to exactly what was requested
- do not alter unrelated settings
- report exactly what changed
- report whether a restart or deployment was triggered

## Audit Workflow

For the AndUs audit, use this workflow:

1. Investigate the current implementation.
2. Show the relevant code or configuration.
3. Explain the smallest safe fix.
4. List the files expected to change.
5. Make only the approved changes.
6. Run focused verification.
7. Run broader verification if appropriate.
8. Show the diff.
9. Stop before commit, push, or deployment unless explicitly instructed.

Do not combine multiple audit steps into one implementation unless explicitly requested.