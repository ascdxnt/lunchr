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

## setup

there are three ways to run this project. pick whichever fits your environment:

| option | what you install locally | guide |
|--------|------------------------|-------|
| pure php + mysql | php, mysql | [getting started — option a](docs/getting-started.md#option-a-pure-php--mysql) |
| db only in docker | php, docker | [getting started — option b](docs/getting-started.md#option-b-php-local--mysql-in-docker) |
| full docker | docker | [docker guide](docs/docker-guide.md) |

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

[MIT](LICENSE)
