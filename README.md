# Laravel Skeleton

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?logo=php)](https://php.net)
[![Livewire](https://img.shields.io/badge/Livewire-Flux-FB70A9?logo=livewire)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4.x-38B2AC?logo=tailwind-css)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

[![Tests](https://github.com/jewei/laravel-skeleton/actions/workflows/tests.yml/badge.svg)](https://github.com/jewei/laravel-skeleton/actions/workflows/tests.yml)
[![Static analysis](https://github.com/jewei/laravel-skeleton/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/jewei/laravel-skeleton/actions/workflows/static-analysis.yml)

Start a new PHP project by scaffolding a Laravel, Livewire, and Tailwind CSS. It combines the latest technologies and best practices.

## 🚀 Features

- User authentication and authorization
- Profile management and customization
- Appearance themes and settings
- Responsive design across all devices
- Modern UI with Tailwind CSS
- Real-time interactions with Livewire Flux
- Secure user authentication flow

## 🔧 Tooling

- Github Action workflow
    - run tests
    - static analysis
    - code formatting fix
- Eloquent:
    - Unguard
    - Strict mode
    - Automatically eagerLoad relationships
- Pest Testing:
    - Architecture testing
    - LazilyRefreshDatabase
    - HTTP Client: Prevents stray requests
- Pint: Styling with Laravel preset
- Rector: Laravel upgrade rules set
- Middlewares: Collection of useful middlewares
- Deployment script
- Cursor rules

## 📖 Usage

Scaffold the Laravel app via composer:

```bash
composer create-project jewei/laravel-skeleton
```

Start the development server:
```bash
composer dev
```

Run the test suite:
```bash
composer test
```

Fix code style issues:
```bash
composer fix
```

## 🛠️ Tall Stack

- **Backend:** PHP 8.3, Laravel 12
- **Frontend:** Livewire Flux, Tailwind CSS 4
- **Development:** Vite, Pint, Rector, PHPStan
- **Testing:** Pest PHP, Peck

## 📋 Requirements

- PHP 8.3+
- Composer
- NPM or preferably Bun
- PostgreSQL/SQLite

## What's next?

1. Update this file README.md to reflect your new project.
2. Find out more on Laravel's documentation about [next step](https://laravel.com/docs/master#next-steps).

## 🙏 Acknowledgements

- [Laravel](https://laravel.com) - The web framework used
- [Livewire](https://livewire.laravel.com) - Full-stack framework for Laravel
- [Tailwind CSS](https://tailwindcss.com) - CSS framework
- [Pest PHP](https://pestphp.com) - Testing framework
- [Peck](https://peckphp.com) - Additional testing tools
- All contributors who have helped shape this project
