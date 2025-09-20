# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Primary Development
- `composer dev` - Start full development environment (Laravel server, queue worker, logs, Vite)
- `composer dev:ssr` - Start SSR development environment
- `npm run dev` - Start Vite development server only
- `npm run build` - Build frontend assets for production
- `npm run build:ssr` - Build frontend assets with SSR support

### Code Quality
- `npm run lint` - Run ESLint and auto-fix issues
- `npm run type:check` - Run TypeScript type checking
- `npm run format` - Format code with Prettier
- `npm run format:check` - Check code formatting
- `vendor/bin/pint` - Format PHP code (Laravel Pint)
- `/vendor/bin/phpstan analyse --memory-limit=2G` - phpstan checks

### Testing
- `composer test` - Run PHP tests (clears config and runs Pest)
- `php artisan test` - Run Pest tests directly
- `vendor/bin/pest` - Run specific test files

### Database
- `php artisan migrate` - Run database migrations
- `php artisan db:seed` - Seed database

## Architecture

This is a Laravel + Vue application using Inertia.js for seamless SPA-like experience.

### Frontend Architecture
- **Framework**: Vue 3.5 with TypeScript
- **Styling**: Tailwind CSS v4 with custom UI components
- **State Management**: Inertia.js shared data and Vue state
- **Build Tool**: Vite with Laravel integration
- **SSR**: Enabled by default (configurable in `config/inertia.php`)

### Backend Architecture
- **Framework**: Laravel 12 with PHP 8.2+
- **Frontend Integration**: Inertia.js middleware handles Vue page rendering
- **Authentication**: Laravel Breeze-style auth with Inertia
- **Database**: SQLite (development), configured in `config/database.php`
- **Testing**: Pest PHP testing framework
  - Where possible make use of pest datasets to keep tests dry

### Key Files
- `resources/js/app.tsx` - Vue application entry point
- `app/Http/Middleware/HandleInertiaRequests.php` - Shared data provider (auth, ziggy routes, app config)
- `routes/web.php` - Main route definitions
- `routes/auth.php` - Authentication routes
- `routes/settings.php` - User settings routes
- `vite.config.ts` - Frontend build configuration

### Page Structure
- Pages: `resources/js/pages/` (auto-discovered by Inertia)
- Layouts: `resources/js/layouts/` (auth, app, settings)
- Components: `resources/js/components/` (reusable UI components)
- Hooks: `resources/js/hooks/` (custom Vue hooks)

### Styling System
- Tailwind CSS v4 with CSS-in-JS support
- Component variants using `class-variance-authority`
- Appearance system with light/dark mode support

### Development process

## Backend
- Read ./laravel-php-guidelines.md and use this as reference for any laravel code
- All backend code must be feature/unit tested
- Your task is only complete once you have:
  - Run all tests and they pass
  - Run phpstan and fixed any errors
  - Run pint
- When interacting with arrays always use Laravel's Arr facade

## Front end
- All code should adhere to typescript definitions
- Your task is only complete once you have:
  - Run type check
  - Run prettier

