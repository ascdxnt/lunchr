# Getting Started

This guide covers setting up SCMS (School Canteen Management System) for local development using PHP and MySQL directly on your machine.

If you prefer to run everything inside Docker containers instead, see [Docker Guide](./docker-guide.md).

## Prerequisites

- **PHP 8.1+** with the `mysqli` extension enabled
- **MySQL 8.0+** (or MariaDB 10.5+)
- A terminal (Command Prompt, PowerShell, Bash, etc.)

### Verify PHP

```bash
php -v
php -m | grep mysqli
```

If `mysqli` is not listed, enable it in your `php.ini`:

```ini
extension=mysqli
```

### Verify MySQL

```bash
mysql --version
```

## 1. Clone the Repository

```bash
git clone <repository-url> scms
cd scms
```

## 2. Create the Database

Connect to MySQL and import the schema:

```bash
mysql -u root -p
```

Inside the MySQL shell:

```sql
CREATE DATABASE IF NOT EXISTS COMEDOR;
USE COMEDOR;
SOURCE querys.sql;
EXIT;
```

Or as a single command:

```bash
mysql -u root -p COMEDOR < querys.sql
```

> If the database `COMEDOR` does not exist yet, create it first:
> ```bash
> mysql -u root -p -e "CREATE DATABASE COMEDOR;"
> mysql -u root -p COMEDOR < querys.sql
> ```

## 3. Configure Environment Variables

Copy the example file and fill in your database credentials:

```bash
cp .env.example .env
```

Edit `.env`:

```env
DB_HOST=localhost
DB_USER=root
DB_PASS=your_mysql_password
DB_NAME=COMEDOR
```

If your MySQL has no password (common in local dev), leave `DB_PASS` empty:

```env
DB_PASS=
```

## 4. Start the Development Server

Use PHP's built-in web server:

```bash
php -S localhost:8080
```

Open your browser and go to: **http://localhost:8080**

## 5. Create an Initial Admin User

There is no registration page. You need to insert an admin user directly into the database.

Generate a password hash first:

```bash
php -r "echo password_hash('admin123', PASSWORD_DEFAULT) . PHP_EOL;"
```

Copy the output hash and insert the admin record:

```sql
INSERT INTO FUNCIONARIO (PERFIL, NOMBRE, PRIMERAPELLIDO, SEGUNDOAPELLIDO, CORREO, CONTRASENA, ESTADO)
VALUES (1, 'Admin', 'Admin', 'Admin', 'admin@example.com', '<paste_hash_here>', 1);
```

Now log in at http://localhost:8080 with:
- **Email:** `admin@example.com`
- **Password:** `admin123`

## Project Structure

```
index.php              # Entry point / front controller
Core/                  # Router, default routes, password config
Controller/            # MVC controllers
  admin/               #   Admin CRUD controllers
  billing/             #   Billing controllers
  client/              #   Client-facing controllers
  helpers/             #   Utility controllers (email, password, dates, photos)
Model/                 # MVC models
  Connection.php       #   Database connection (mysqli + .env support)
  Entities/            #   Entity classes (plain PHP objects)
  Methods/             #   Data access classes (queries per entity)
View/                  # MVC views
  views/               #   PHP view templates
    admin/             #     Admin views
    billing/           #     Billing views
    client/            #     Client views
    components/        #     Reusable components (Head, Header, menus)
  css/                 #   Stylesheets
  js/                  #   JavaScript
  assets/              #   Images, audio, favicons, profile photos
```

## User Roles

| Role          | Profile ID | Access                    |
|---------------|------------|---------------------------|
| Administrator | 1          | Full system management    |
| Billing Staff | 2          | Meal billing operations   |
| Client        | 3          | Students and teachers     |

## Troubleshooting

### "Connection error" on page load

- Verify MySQL is running
- Check `.env` credentials match your MySQL setup
- Ensure the `COMEDOR` database exists and the schema was imported

### Blank page or 500 error

- Check PHP error logs: `php -S localhost:8080 2>&1`
- Ensure `mysqli` extension is enabled in `php.ini`
- Verify all files are present (especially `Core/`, `Controller/`, `Model/`, `View/`)

### Profile photos not loading

- Ensure `View/assets/profile/` directory exists
- The default photo is `View/assets/profile/default.jpg`
