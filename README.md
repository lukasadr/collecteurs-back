# Collecteurs Back

Backend API for the Collecteurs project, built with Laravel 11 and PHP 8.2.

This repository contains the server-side application logic, authentication, API routes, and data access layer for the Collecteurs platform.

## Stack

- PHP 8.2
- Laravel 11
- Laravel Sanctum for authentication
- MySQL / PostgreSQL / SQLite-ready database support
- PHPUnit for automated testing
- Vite for frontend asset tooling

## Features

- REST API endpoints for project workflows
- Authentication and token-based access
- Database-backed application logic
- Maintainable Laravel architecture for rapid development
- Extensible service and controller structure

## Getting started

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Project structure

- `app/` — application logic, models, controllers, and services
- `config/` — framework and environment configuration
- `database/` — migrations, seeders, and database-related logic
- `routes/` — API and web route definitions
- `resources/` — templates and frontend assets
- `tests/` — automated test suite

## License

This project is open source and available under the MIT License.
