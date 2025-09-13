# Street Group Tech Test

A Laravel + Vue.js application using Inertia.js for seamless SPA-like experience.

## Prerequisites

- PHP 8.2+
- Node.js 18+
- Composer
- SQLite

## Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd street-group-tech-test
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

## Development

### Start the development environment
```bash
composer dev
```
This starts Laravel server, queue worker, logs, and Vite development server.

### Alternative development commands
- `composer dev:ssr` - Start with SSR support
- `npm run dev` - Start Vite development server only

## Code Quality

### Run all quality checks
```bash
# PHP
vendor/bin/pint
vendor/bin/phpstan analyse --memory-limit=2G
composer test

# Frontend
npm run lint
npm run type:check
npm run format
```

## Testing

```bash
# PHP tests
composer test
# or
php artisan test

# Specific test files
vendor/bin/pest path/to/test
```

## Building for Production

```bash
npm run build
# or with SSR
npm run build:ssr
```

## Architecture

- **Backend**: Laravel 12 with PHP 8.2+
- **Frontend**: Vue 3.5 with TypeScript
- **Styling**: Tailwind CSS v4
- **Database**: SQLite (development)
- **Testing**: Pest PHP testing framework
- **Build Tool**: Vite

## Project Structure

- `app/` - Laravel application code
- `resources/js/` - Vue.js frontend code
  - `pages/` - Inertia.js pages
  - `components/` - Reusable Vue components
  - `layouts/` - Page layouts
- `routes/` - Laravel routes
- `tests/` - PHP tests
- `database/` - Migrations and seeders

## Project file reference

- `app/Http/Controllers/HomeOwnerController.php` - Main controller that handles inertia response for csv upload
- `app/Services/HomeOwnerDataService.php` - Service to handle csv parsing logic
- `app/DataTransferObjects/Person.php` - Person DTO
- `app/Http/Requests/CsvUploadRequest.php` - Upload FormRequest
- `resources/js/pages/Welcome.vue` - Main page upload form
- `resources/js/pages/HomeOwners/Results.vue` - Results page from csv upload
- `resources/js/components/csv` - Component folder for csv upload
- `resources/js/components/results` - Component folder for results
