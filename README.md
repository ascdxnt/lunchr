# scms

school cafeteria management system — a php web application for managing student cafeteria services, meals, billing, and attendance tracking.

## features

- role-based access: administrator, billing staff, and client (student/teacher)
- student and teacher management with excel bulk import
- meal attendance tracking and billing
- non-school day calendar management
- statistics dashboard
- profile photo uploads
- automated password generation and email notifications

## quick start

```bash
cp .env.example .env
# edit .env with your database credentials
php -S localhost:8080
```

see [docs/getting-started.md](docs/getting-started.md) for the full setup guide.

## docker

```bash
cp .env.example .env
# edit .env: set DB_HOST=db, DB_PASS=root
docker compose up -d
```

see [docs/docker-guide.md](docs/docker-guide.md) for the full docker tutorial.

## documentation

- [getting started](docs/getting-started.md) — local php + mysql setup
- [docker guide](docs/docker-guide.md) — run everything in containers

## tech stack

- php 8.x (no frameworks, no composer dependencies)
- mysql 8.0
- bootstrap 5.1.3, fontawesome, alertifyjs
- vanilla javascript

## project structure

```
index.php           entry point
Core/               router, constants, config
Controller/         mvc controllers
  admin/            admin crud controllers
  billing/          billing controllers
  client/           client-facing controllers
  helpers/          utility controllers
Model/              mvc models
  Connection.php    database connection
  Entities/         entity classes
  Methods/          data access classes
View/               mvc views
  views/            php templates
  css/              stylesheets
  js/               javascript
  assets/           images, audio, favicons
docs/               documentation
```

## license

[mit](LICENSE)
