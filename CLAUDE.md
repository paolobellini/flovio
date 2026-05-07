# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Flovio is a self-hosted email marketing platform built for agencies and businesses that need full control over their campaigns. Built with Laravel 13 + Livewire 4 + Flux UI (free edition). Italian locale (`it`) with English fallback.

Key features: design newsletters with AI assistance, schedule and automate sends, track opens and clicks in real time, and review content automatically before delivery — all powered by the Mailgun API.

## Commands

All commands run through Sail (`vendor/bin/sail`). Key composer scripts:

```bash
composer run dev          # Start dev server, queue, pail logs, and Vite concurrently
composer test             # Config clear + lint check + pest
composer tests            # Type coverage + code coverage checks
composer coverage         # Pest with coverage (min 90%)
composer type             # Type coverage check (min 90%)
composer lint             # Pint formatter (parallel)
composer lint:check       # Pint dry-run
composer analyse          # PHPStan (max level, 2G memory)
composer refactor         # Rector refactoring
composer cleanup          # lint + tests + analyse + refactor checks
composer setup            # Full project setup (install, env, key, migrate, build)
```

Run a single test: `vendor/bin/sail artisan test --compact --filter=testName`

## Quality Thresholds

- **Code coverage**: minimum 90% (enforced by `composer coverage`)
- **Type coverage**: minimum 90% (enforced by `composer type`)
- **PHPStan**: level max with larastan extension
- **Pint**: laravel preset with strict types, final classes, PHPDoc-only annotations
- **Rector**: Laravel code quality, collections, type declarations, eloquent, testing sets

## Architecture

**Authentication**: Laravel Fortify (backend) + Livewire components (frontend). Features: login, registration, email verification, password reset, 2FA (TOTP with confirmation). User model uses `TwoFactorAuthenticatable`. Rate limiting: 5/min for login and 2FA. Custom `LoginResponse` redirects onboarded users to dashboard, non-onboarded to onboarding. Custom `RegisterResponse` always redirects to onboarding.

**Onboarding**: Multi-step Livewire wizard (`app/Livewire/Onboarding/Wizard.php`) using the auth split layout. Steps: profile (name, company, timezone) → SMTP/Mailgun settings (API key, domain, sender) → summary. Protected by `RedirectIfOnboarded` middleware. Completion sets `onboarded_at` on user.

**Routing**: `routes/web.php` (public + auth-gated dashboard/onboarding) includes `routes/settings.php` (profile, appearance, security). Dashboard and settings routes require `EnsureUserIsOnboarded` middleware. Security settings require password confirmation when 2FA management is enabled.

**Livewire Components**: `app/Livewire/Settings/` (Profile, Security, Appearance, DeleteUserForm), `app/Livewire/Settings/TwoFactor/` (RecoveryCodes), `app/Livewire/Onboarding/` (Wizard), `app/Livewire/Actions/` (Logout). Shared validation via traits in `app/Concerns/`.

**Models**: `User` (name, email, company_name, timezone, onboarded_at) with `SmtpSetting` hasOne relationship. `SmtpSetting` (api_key encrypted, domain, sender_name, sender_email) belongs to User. All model fields must be explicitly cast in `casts()` method. All models must have `@property-read` PHPDoc for relationships.

**Actions**: Business logic in single-purpose action classes under `app/Actions/`. Create with `vendor/bin/sail artisan make:action ActionName`. Actions use `handle()` method. Onboarding actions: `UpdateProfileAction`, `StoreSmtpSettingAction`, `CompleteOnboardingAction`.

**Form Requests**: Validation rules in dedicated FormRequest classes under `app/Http/Requests/`. Used by Livewire via `(new RequestClass())->rules()`. Onboarding requests: `ProfileRequest`, `SmtpSettingRequest`.

**Database**: PostgreSQL 18 (primary: `pgsql` service, testing: `pgsql_test` service on port 5433→5432). Redis for cache (predis client). Sessions and queue use database driver.

**CI**: GitHub Actions runs linting (PHP 8.4) and tests (PHP 8.3, 8.4, 8.5 matrix). Flux credentials from secrets.

## Design System

**Color palette**: Wine-themed colors (`wine-50` through `wine-950`) defined in `resources/css/app.css`. Accent color: `#7B2D42` (wine-800).

**Auth pages**: Split layout (`layouts/auth/split.blade.php`) — form on the left, branded panel on the right with animated gradient blobs, feature pills, and glassmorphism effects. All auth pages (login, register, forgot-password, reset-password, verify-email, two-factor-challenge, onboarding) use this layout with centered title/subtitle.

**App logo**: Paper plane SVG icon (`app-logo-icon.blade.php`), uses `fill="currentColor"` for color inheritance. Brand name: "Flovio".

**Validation errors**: Custom Flux error override (`resources/views/flux/error.blade.php`) — small (`text-xs`), minimal top margin (`mt-1`), no icon, red text.

## Conventions

### Naming

- Action test files: named without "Action" suffix (e.g., `UpdateProfileTest.php` not `UpdateProfileActionTest.php`)
- Action unit tests go in `tests/Unit/Actions/`
- Form request unit tests go in `tests/Unit/Http/Requests/`
- Feature tests for full flows go in `tests/Feature/`

### Testing

- Write tests first (TDD when fixing bugs) — create the failing test, then fix the code
- Use `assertDatabaseHas` in feature tests to verify persistence
- Form request tests: one test for valid data, one dataset test for a few representative invalid cases (not exhaustive)
- Action unit tests: one test per action, named without "Action" suffix
- Use `User::factory()->onboarded()->create()` for tests that access dashboard/settings
- Use `__()` helper in test assertions when checking translated text on pages
- Feature tests should test the full flow end-to-end

### Translations

- All user-facing strings must use `__()` helper
- Italian translations in `lang/it.json` (JSON for Blade `__()` strings) and `lang/it/*.php` (for framework messages)
- Add Italian translations immediately when adding new UI text

### Models

- All fields must be explicitly cast in `casts()` including `id`, `created_at`, `updated_at`
- Add `@property-read` PHPDoc annotations for all relationships
- Sensitive fields use `encrypted` cast (e.g., `api_key`)
- Hidden fields use `#[Hidden]` attribute

### Dependency Injection

- Use `#[CurrentUser]` attribute from `Illuminate\Container\Attributes\CurrentUser` to inject authenticated user in Livewire action methods

### Jira

- Project key: `FLV` on `bellini.atlassian.net` (cloud ID: `edb9af39-1c29-4229-b847-5d71ce55e973`)

## Email Delivery & Tracking Architecture

**Mailgun integration**: Mailgun is used only as a delivery pipe. No contacts or lists are synced to Mailgun. The only Mailgun API calls are `POST /messages` at send time and incoming webhooks for event tracking.

**Campaign sending**: Contacts and lists live entirely in the local database. When a campaign is sent, a queued job chunks the target list into batches of max 1,000 recipients (Mailgun's per-request limit) and sends each batch via the Mailgun Messages API with `recipient-variables` for personalization.

**Event tracking**: `campaign_sends` table stores one row per contact per campaign with separate timestamp columns for each event stage: `sent_at`, `delivered_at`, `opened_at`, `clicked_at`, `bounced_at`, `complained_at`, `unsubscribed_at`. Mailgun webhooks update the corresponding column — nothing is overwritten, all events are preserved. This allows accurate cumulative stats (e.g. a clicked email is also counted as delivered and opened).

**Contact stats**: Aggregated from `campaign_sends` using `COUNT(opened_at)`, `COUNT(clicked_at)`, etc. Stats feed the UI, AI insights, and automatic list segmentation based on contact engagement patterns.

---

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.5
- laravel/ai (AI) - v0
- laravel/fortify (FORTIFY) - v1
- laravel/framework (LARAVEL) - v13
- laravel/prompts (PROMPTS) - v0
- livewire/flux (FLUXUI_FREE) - v2
- livewire/livewire (LIVEWIRE) - v4
- larastan/larastan (LARASTAN) - v3
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- rector/rector (RECTOR) - v2
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `ai-sdk-development` — TRIGGER when working with ai-sdk which is Laravel official first-party AI SDK. Activate when building, editing AI agents, chatbots, text generation, image generation, audio/TTS, transcription/STT, embeddings, RAG, vector stores, reranking, structured output, streaming, conversation memory, tools, queueing, broadcasting, and provider failover across OpenAI, Anthropic, Gemini, Azure, Groq, xAI, DeepSeek, Mistral, Ollama, ElevenLabs, Cohere, Jina, and VoyageAI. Invoke when the user references ai-sdk, the `Laravel\Ai\` namespace, or this project's AI features — not for Prism PHP or other AI packages used directly.
- `fortify-development` — ACTIVATE when the user works on authentication in Laravel. This includes login, registration, password reset, email verification, two-factor authentication (2FA/TOTP/QR codes/recovery codes), profile updates, password confirmation, or any auth-related routes and controllers. Activate when the user mentions Fortify, auth, authentication, login, register, signup, forgot password, verify email, 2FA, or references app/Actions/Fortify/, CreateNewUser, UpdateUserProfileInformation, FortifyServiceProvider, config/fortify.php, or auth guards. Fortify is the frontend-agnostic authentication backend for Laravel that registers all auth routes and controllers. Also activate when building SPA or headless authentication, customizing login redirects, overriding response contracts like LoginResponse, or configuring login throttling. Do NOT activate for Laravel Passport (OAuth2 API tokens), Socialite (OAuth social login), or non-auth Laravel features.
- `laravel-best-practices` — Apply this skill whenever writing, reviewing, or refactoring Laravel PHP code. This includes creating or modifying controllers, models, migrations, form requests, policies, jobs, scheduled commands, service classes, and Eloquent queries. Triggers for N+1 and query performance issues, caching strategies, authorization and security patterns, validation, error handling, queue and job configuration, route definitions, and architectural decisions. Also use for Laravel code reviews and refactoring existing Laravel code to follow best practices. Covers any task involving Laravel backend PHP code patterns.
- `fluxui-development` — Use this skill for Flux UI development in Livewire applications only. Trigger when working with <flux:*> components, building or customizing Livewire component UIs, creating forms, modals, tables, or other interactive elements. Covers: flux: components (buttons, inputs, modals, forms, tables, date-pickers, kanban, badges, tooltips, etc.), component composition, Tailwind CSS styling, Heroicons/Lucide icon integration, validation patterns, responsive design, and theming. Do not use for non-Livewire frameworks or non-component styling.
- `livewire-development` — Use for any task or question involving Livewire. Activate if user mentions Livewire, wire: directives, or Livewire-specific concepts like wire:model, wire:click, wire:sort, or islands, invoke this skill. Covers building new components, debugging reactivity issues, real-time form validation, drag-and-drop, loading states, migrating from Livewire 3 to 4, converting component formats (SFC/MFC/class-based), and performance optimization. Do not use for non-Livewire reactive UI (React, Vue, Alpine-only, Inertia.js) or standard Laravel forms without Livewire.
- `pest-testing` — Use this skill for Pest PHP testing in Laravel projects only. Trigger whenever any test is being written, edited, fixed, or refactored — including fixing tests that broke after a code change, adding assertions, converting PHPUnit to Pest, adding datasets, and TDD workflows. Always activate when the user asks how to write something in Pest, mentions test files or directories (tests/Feature, tests/Unit, tests/Browser), or needs browser testing, smoke testing multiple pages for JS errors, or architecture tests. Covers: test()/it()/expect() syntax, datasets, mocking, browser testing (visit/click/fill), smoke testing, arch(), Livewire component tests, RefreshDatabase, and all Pest 4 features. Do not use for factories, seeders, migrations, controllers, models, or non-test PHP code.
- `tailwindcss-development` — Always invoke when the user's message includes 'tailwind' in any form. Also invoke for: building responsive grid layouts (multi-column card grids, product grids), flex/grid page structures (dashboards with sidebars, fixed topbars, mobile-toggle navs), styling UI components (cards, tables, navbars, pricing sections, forms, inputs, badges), adding dark mode variants, fixing spacing or typography, and Tailwind v3/v4 work. The core use case: writing or fixing Tailwind utility classes in HTML templates (Blade, JSX, Vue). Skip for backend PHP logic, database queries, API routes, JavaScript with no HTML/CSS component, CSS file audits, build tool configuration, and vanilla CSS.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `vendor/bin/sail npm run build`, `vendor/bin/sail npm run dev`, or `vendor/bin/sail composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `vendor/bin/sail artisan route:list`). Use `vendor/bin/sail artisan list` to discover available commands and `vendor/bin/sail artisan [command] --help` to check parameters.
- Inspect routes with `vendor/bin/sail artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `vendor/bin/sail artisan config:show app.name`, `vendor/bin/sail artisan config:show database.default`. Or read config files directly from the `config/` directory.
- To check environment variables, read the `.env` file directly.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `vendor/bin/sail artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `vendor/bin/sail artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== sail rules ===

# Laravel Sail

- This project runs inside Laravel Sail's Docker containers. You MUST execute all commands through Sail.
- Start services using `vendor/bin/sail up -d` and stop them with `vendor/bin/sail stop`.
- Open the application in the browser by running `vendor/bin/sail open`.
- Always prefix PHP, Artisan, Composer, and Node commands with `vendor/bin/sail`. Examples:
    - Run Artisan Commands: `vendor/bin/sail artisan migrate`
    - Install Composer packages: `vendor/bin/sail composer install`
    - Execute Node commands: `vendor/bin/sail npm run dev`
    - Execute PHP scripts: `vendor/bin/sail php [script]`
- View all available Sail commands by running `vendor/bin/sail` without arguments.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `vendor/bin/sail artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `vendor/bin/sail artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `vendor/bin/sail artisan list` and check their parameters with `vendor/bin/sail artisan [command] --help`.
- If you're creating a generic PHP class, use `vendor/bin/sail artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `vendor/bin/sail artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `vendor/bin/sail artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `vendor/bin/sail npm run build` or ask the user to run `vendor/bin/sail npm run dev` or `vendor/bin/sail composer run dev`.

## Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== livewire/core rules ===

# Livewire

- Livewire allow to build dynamic, reactive interfaces in PHP without writing JavaScript.
- You can use Alpine.js for client-side interactions instead of JavaScript frameworks.
- Keep state server-side so the UI reflects it. Validate and authorize in actions as you would in HTTP requests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/sail bin pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/sail bin pint --test --format agent`, simply run `vendor/bin/sail bin pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `vendor/bin/sail artisan make:test --pest {name}`.
- Run tests: `vendor/bin/sail artisan test --compact` or filter: `vendor/bin/sail artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.

</laravel-boost-guidelines>
