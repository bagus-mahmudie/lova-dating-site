# Lova Dating Site

Dating application with user profiles, search, likes, and messaging.

**Status:** Local development; a public demo is not yet available.

## Features

- User profiles, albums, and information
- User search and likes
- Messaging, notifications, and social authentication

## Technology

Laravel 9, Blade, Bootstrap 5, Laravel Mix, MySQL.

## Running Locally

Use PHP ^8.0, Composer, MySQL, and a Node.js version compatible with the bundler version in `package.json`.

```sh
composer install
npm ci
```

Copy `.env.example` to `.env`, create an empty database, and enter your local database configuration. Store email, OAuth, payment, and shipping credentials only in `.env`.

```sh
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run production
php artisan serve
```

Open [localhost:8000](http://localhost:8000). Use a development database; real user data, uploads, and account credentials are not included. Seeders do not provide a fixed login password; configure your own development account.

External integrations require accounts and configuration for their respective services. Use sandbox mode where available.

## Structure

- `app/` — models, controllers, and application logic.
- `resources/` — views and frontend assets.
- `routes/` — application routes.
- `database/` — migrations, factories, and seeders.
- `tests/` — tests available in the project.

## Checks

Run `php artisan test` in a configured development environment. The presence of tests in the repository does not mean that all external integrations have been tested.

## Repository Notes

Installed dependencies, caches, `.env` files, databases, and user uploads are not included. Brand names and third-party assets remain subject to their respective owners' rights. This repository does not grant a license for reuse.
