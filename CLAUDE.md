# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

### Full Setup
```bash
composer setup      # Install deps, generate app key, migrate, install npm deps, build frontend
```

### Development
```bash
composer dev        # Concurrent: Laravel server + queue worker + Vite dev server
```

### Frontend
```bash
pnpm run dev            # Vite dev server only
pnpm run build          # Production build
pnpm run build:ssr      # Production build with SSR
pnpm run lint           # Fix ESLint issues
pnpm run lint:check     # Check ESLint without fixing
pnpm run format         # Format with Prettier
pnpm run format:check   # Check formatting without fixing
pnpm run types:check    # TypeScript check (vue-tsc)
```

### Backend
```bash
composer lint           # Fix PHP code style (Pint)
composer lint:check     # Check PHP code style without fixing
php artisan migrate     # Run database migrations
php artisan test        # Run Pest tests
```

### CI
```bash
composer ci:check   # Runs: ESLint check → Prettier check → TypeScript check → Pest tests
```

### Run a single test
```bash
php artisan test --filter=TestNameHere
```

## Architecture

**Stack**: Laravel 13 + Vue 3 + Inertia.js + TypeScript + Tailwind CSS 4 + Reka UI (shadcn-vue)

**Package manager**: pnpm (see `pnpm-workspace.yaml`)

### How Inertia connects backend to frontend

Inertia eliminates the need for a separate API. Controllers return `Inertia::render('PageName', $props)` which maps to a Vue component in `resources/js/pages/`. No JSON API layer exists — data flows from controllers as page props.

- Routes: `routes/web.php` and `routes/settings.php`
- Controllers: `app/Http/Controllers/` (Settings controllers under `Settings/` subdirectory)
- Pages (frontend): `resources/js/pages/` — filenames map to controller render calls
- Layouts: `resources/js/layouts/` — `AppLayout.vue` (authenticated), `AuthLayout.vue` (guest), `settings/Layout.vue` (nested)

### Authentication

Handled by **Laravel Fortify** (`config/fortify.php`, `app/Providers/FortifyServiceProvider.php`):
- Actions in `app/Actions/Fortify/` override default Fortify behavior
- 2FA support is included and wired into `App\Models\User`
- Auth pages live in `resources/js/pages/auth/`

### Frontend component conventions

- `resources/js/components/ui/` — shadcn-vue primitives built on Reka UI (headless). These are generated/managed components, ignored by ESLint.
- `resources/js/components/` — custom application components
- `resources/js/composables/` — Vue 3 composables (e.g., `useAppearance.ts` for dark/light mode, `useTwoFactorAuth.ts`)
- `resources/js/types/` — TypeScript definitions; `global.d.ts` augments global types

### Path aliases

TypeScript and Vite both resolve `@/*` → `resources/js/*`.

### Database

Default: SQLite. Tests use an in-memory SQLite database (configured in `phpunit.xml`). Factories in `database/factories/`, seeders in `database/seeders/`.

### Form validation flow

Server-side validation via `app/Http/Requests/` classes. Validation errors are returned to Inertia and surfaced in Vue components via `useForm()` from `@inertiajs/vue3`. Shared logic/traits live in `app/Concerns/`.
