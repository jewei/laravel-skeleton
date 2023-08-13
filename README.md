# Laravel Skeleton

[![Tests](https://github.com/jewei/laravel-skeleton/actions/workflows/tests.yml/badge.svg)](https://github.com/jewei/laravel-skeleton/actions/workflows/tests.yml)
[![Static analysis](https://github.com/jewei/laravel-skeleton/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/jewei/laravel-skeleton/actions/workflows/static-analysis.yml)

Start a new project by scaffolding a PHP 8.2, Laravel 10 app. It combines the latest technologies and best practices.

## Usage

You can scaffold the Laravel app via composer:

```
composer create-project jewei/laravel-skeleton
```

## What's included?

1. Github Action workflow: run tests, static analysis and code formatting fix.
2. Configured Eloquent Strictness.
3. Pest: Architecture Testing.
4. Test: Use LazilyRefreshDatabase instead of RefreshDatabase.
5. HTTP Client: Prevents stray requests.
6. Pint: Styling with Laravel preset.
7. Rector: Laravel upgrade rules set.
8. Middleware: ResponseWithJson for APIs.
9. Removed Sail - Docker is complicated.

## What's next?

1. update this file README.md to reflect your new project.
2. Install a Laravel starter kit of your choice maybe?

Breeze for simple app.

```
composer require laravel/breeze --dev
php artisan breeze:install
```

Jetstream for full fledge app.

```
composer require laravel/jetstream
php artisan jetstream:install livewire --dark --teams
```