<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects# Standalone HRM

Standalone HRM is a Laravel-based Human Resource Management API for managing employees, jobs, positions, skills, evaluations, and leave workflows. The system is designed as a backend foundation for an HR department or internal company HR portal.

## Features

- Employee management
- Job and position management
- Department and establishment data model
- Skills management
- Job skill requirements
- Employee skill tracking
- Employee skill gap analysis
- Employee evaluations
- Leave type management
- Leave balance tracking
- Leave request management
- Leave approval and rejection workflow
- Basic dashboard endpoint/view
- Seeded demo data support

## Tech Stack

- PHP 8.3+
- Laravel 13
- SQLite by default for local development
- Composer
- Node.js and npm
- Vite
- Tailwind CSS

## Requirements

Make sure the following are installed on your machine:

- PHP 8.3 or newer
- Composer
- Node.js and npm
- SQLite, or PostgreSQL/MySQL if you choose to change the database later

Required PHP extensions may include:

- `pdo_sqlite` for SQLite
- `gd` for packages such as PHPWord or PhpSpreadsheet if those packages are used
- `pdo_pgsql` and `pgsql` if switching to PostgreSQL later

You can check enabled PHP extensions with:

```bash
php -m
```

## Installation

Clone the repository:

```bash
git clone <your-repository-url>
cd Standalone-HRM-main
```

Install PHP dependencies:

```bash
composer install
```

Install JavaScript dependencies:

```bash
npm install
```

Copy the environment file:

```bash
cp .env.example .env
```

On Windows PowerShell, use:

```powershell
copy .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

## Database Setup

By default, the project is configured for SQLite.

Create the SQLite database file:

```bash
mkdir -p database
touch database/database.sqlite
```

On Windows PowerShell, use:

```powershell
New-Item -ItemType File -Path database/database.sqlite -Force
```

Make sure your `.env` file contains:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Run the migrations:

```bash
php artisan migrate
```

To reset the database and load demo data:

```bash
php artisan migrate:fresh --seed
```


## Demo Login Details

The database seeder creates the following generic test account after you run the seed command:

```bash
php artisan migrate:fresh --seed
```

| Role | Name | Email / Username | Password |
|---|---|---|---|
| Super Admin | System Admin | `admin@hrm.local` | `password` |

These credentials come from `database/seeders/AdminUserSeeder.php`. The seeded roles are:

- Super Admin
- HR Admin
- Manager
- Employee

> Development note: this is a demo password only. Change it before using the system in production or before sharing a deployed version publicly.

At the moment, the project has seeded admin credentials and role data, but the visible web routes/API routes are not fully protected by a completed login flow yet. The main dashboard route is currently `/`, and the API endpoints are available under `/api/...`.

## Running the Application

Start the Laravel development server:

```bash
php artisan serve
```

Start the Vite development server in a second terminal:

```bash
npm run dev
```

The application should be available at:

```text
http://127.0.0.1:8000
```

## API Endpoints

The API routes are registered in `routes/api.php`.

### Employees

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/employees` | List employees |
| POST | `/api/employees` | Create an employee |
| GET | `/api/employees/{employee}` | View one employee |
| PUT/PATCH | `/api/employees/{employee}` | Update an employee |
| DELETE | `/api/employees/{employee}` | Delete an employee |
| POST | `/api/employees/{employee}/skills` | Attach a skill to an employee |
| GET | `/api/employees/{employee}/skill-gap` | View employee skill gaps |

### Jobs

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/jobs` | List jobs |
| POST | `/api/jobs` | Create a job |
| GET | `/api/jobs/{job}` | View one job |
| PUT/PATCH | `/api/jobs/{job}` | Update a job |
| DELETE | `/api/jobs/{job}` | Delete a job |
| POST | `/api/jobs/{job}/skills` | Attach a required skill to a job |

### Skills

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/skills` | List skills |
| POST | `/api/skills` | Create a skill |
| GET | `/api/skills/{skill}` | View one skill |
| PUT/PATCH | `/api/skills/{skill}` | Update a skill |
| DELETE | `/api/skills/{skill}` | Delete a skill |

### Positions

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/positions` | List positions |
| POST | `/api/positions` | Create a position |
| GET | `/api/positions/{position}` | View one position |
| PUT/PATCH | `/api/positions/{position}` | Update a position |
| DELETE | `/api/positions/{position}` | Delete a position |

### Evaluations

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/evaluations` | List evaluations |
| POST | `/api/evaluations` | Create an evaluation |
| GET | `/api/evaluations/{evaluation}` | View one evaluation |
| PUT/PATCH | `/api/evaluations/{evaluation}` | Update an evaluation |
| DELETE | `/api/evaluations/{evaluation}` | Delete an evaluation |

### Leave Management

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/leave-types` | List leave types |
| POST | `/api/leave-types` | Create a leave type |
| GET | `/api/leave-balances` | List leave balances |
| POST | `/api/leave-balances` | Create a leave balance |
| GET | `/api/leave-requests` | List leave requests |
| POST | `/api/leave-requests` | Create a leave request |
| GET | `/api/leave-requests/{leaveRequest}` | View one leave request |
| PUT/PATCH | `/api/leave-requests/{leaveRequest}` | Update a leave request |
| DELETE | `/api/leave-requests/{leaveRequest}` | Delete a leave request |
| POST | `/api/leave-requests/{leaveRequest}/approve` | Approve a leave request |
| POST | `/api/leave-requests/{leaveRequest}/reject` | Reject a leave request |

## Example API Requests

Create a skill:

```bash
curl -X POST http://127.0.0.1:8000/api/skills \
  -H "Content-Type: application/json" \
  -d '{"name":"Laravel","description":"Laravel framework knowledge"}'
```

Attach a skill to an employee:

```bash
curl -X POST http://127.0.0.1:8000/api/employees/1/skills \
  -H "Content-Type: application/json" \
  -d '{"skill_id":1,"proficiency_level":4}'
```

Check an employee skill gap:

```bash
curl http://127.0.0.1:8000/api/employees/1/skill-gap
```

Approve a leave request:

```bash
curl -X POST http://127.0.0.1:8000/api/leave-requests/1/approve \
  -H "Content-Type: application/json" \
  -d '{"approved_by":1,"comments":"Approved"}'
```

Reject a leave request:

```bash
curl -X POST http://127.0.0.1:8000/api/leave-requests/1/reject \
  -H "Content-Type: application/json" \
  -d '{"approved_by":1,"comments":"Insufficient leave balance"}'
```

## Project Structure

```text
app/
  Http/Controllers/      API and dashboard controllers
  Models/                Eloquent models

database/
  migrations/            Database table definitions
  seeders/               Demo and admin seed data

routes/
  api.php                API routes
  web.php                Web routes

resources/
  css/                   Frontend styles
  js/                    Frontend JavaScript
```

## Development Commands

Run the backend server:

```bash
php artisan serve
```

Run frontend assets in development mode:

```bash
npm run dev
```

Build frontend assets:

```bash
npm run build
```

Run tests:

```bash
php artisan test
```

Format code with Laravel Pint:

```bash
./vendor/bin/pint
```

On Windows PowerShell:

```powershell
vendor\bin\pint
```

## Current Development Notes

Before using this project in production, the following improvements are recommended:

- Add authentication for API routes.
- Add role-based access control for HR admins, managers, and employees.
- Remove misplaced controller files from `app/Models`.
- Remove duplicate or incorrectly named model files.
- Add request validation classes for cleaner controller code.
- Add feature tests for employees, jobs, skills, evaluations, and leave workflows.
- Update the application name in `.env.example` from `Laravel` to `Standalone HRM`.
- Replace demo seed data with realistic sample data where appropriate.
- Add PostgreSQL support for production deployment.

## Optional: Authentication and Permissions

The codebase references Laravel Sanctum and Spatie Permission in some places. If you use those features, install the packages:

```bash
composer require laravel/sanctum spatie/laravel-permission
```

Publish their migrations/configuration:

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

Then protect API routes with middleware such as:

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('employees', EmployeeController::class);
});
```

## Optional: Switching to PostgreSQL Later

Update `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=standalone_hrm
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

Enable these PHP extensions in `php.ini`:

```ini
extension=pdo_pgsql
extension=pgsql
```

Then clear Laravel config and migrate:

```bash
php artisan optimize:clear
php artisan migrate:fresh --seed
```

## License

This project is open-source and may be used for learning, demonstration, and further development.
, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
