# Getting Started

This guide covers two ways to run SCMS locally with PHP on your machine. The difference is where MySQL runs.

| Option | PHP | MySQL | You need |
|--------|-----|-------|----------|
| [A — Pure PHP + MySQL](#option-a-pure-php--mysql) | local | local | PHP, MySQL |
| [B — PHP local + MySQL in Docker](#option-b-php-local--mysql-in-docker) | local | Docker container | PHP, Docker |

If you want **everything** in Docker (no local PHP either), see the [Docker Guide](./docker-guide.md) instead.

---

## Option A: Pure PHP + MySQL

Both PHP and MySQL are installed directly on your machine.

### Prerequisites

- **PHP 8.1+** with the `mysqli` extension enabled
- **MySQL 8.0+** (or MariaDB 10.5+)

Verify:

```bash
php -v
php -m | grep mysqli
mysql --version
```

If `mysqli` is not listed, enable it in your `php.ini`:

```ini
extension=mysqli
```

### 1. Clone the repository

```bash
git clone https://github.com/ecx2f/scms.git scms
cd scms
```

### 2. Create the database

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS COMEDOR;"
mysql -u root -p COMEDOR < querys.sql
```

### 3. Configure environment

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

If your MySQL has no password, leave `DB_PASS` empty:

```env
DB_PASS=
```

### 4. Start the server

```bash
php -S localhost:8080
```

Open **http://localhost:8080**.

### 5. Create an admin user

There is no registration page. Insert an admin directly:

```bash
php -r "echo password_hash('admin123', PASSWORD_DEFAULT) . PHP_EOL;"
```

Copy the hash and run:

```bash
mysql -u root -p COMEDOR -e "
  INSERT INTO FUNCIONARIO (PERFIL, NOMBRE, PRIMERAPELLIDO, SEGUNDOAPELLIDO, CORREO, CONTRASENA, ESTADO)
  VALUES (1, 'Admin', 'Admin', 'Admin', 'admin@example.com', '<paste_hash_here>', 1);
"
```

Log in with `admin@example.com` / `admin123`.

---

## Option B: PHP local + MySQL in Docker

PHP runs on your machine, MySQL runs in a Docker container. This is useful if you don't want to install MySQL locally.

### Prerequisites

- **PHP 8.1+** with the `mysqli` extension enabled
- **Docker** and **Docker Compose v2+**

Verify:

```bash
php -v
php -m | grep mysqli
docker --version
docker compose version
```

### 1. Clone the repository

```bash
git clone https://github.com/ecx2f/scms.git scms
cd scms
```

### 2. Start MySQL in Docker

```bash
docker compose -f docker-compose.db.yml up -d
```

This starts a MySQL 8.0 container on port 3306 and automatically imports the schema from `querys.sql`.

Wait a few seconds for MySQL to finish initializing. You can check:

```bash
docker compose -f docker-compose.db.yml logs -f db
```

Look for `ready for connections` in the output, then press `Ctrl+C`.

### 3. Configure environment

```bash
cp .env.example .env
```

Edit `.env`:

```env
DB_HOST=localhost
DB_USER=root
DB_PASS=root
DB_NAME=COMEDOR
```

> `DB_HOST` is `localhost` here because PHP runs on your machine and connects to the container's exposed port.

If port 3306 is already in use, change `DB_PORT` in `.env` and restart the container:

```env
DB_PORT=3307
```

```bash
docker compose -f docker-compose.db.yml down
docker compose -f docker-compose.db.yml up -d
```

### 4. Start the server

```bash
php -S localhost:8080
```

Open **http://localhost:8080**.

### 5. Create an admin user

```bash
php -r "echo password_hash('admin123', PASSWORD_DEFAULT) . PHP_EOL;"
```

Copy the hash and run:

```bash
docker compose -f docker-compose.db.yml exec db mysql -u root -proot COMEDOR -e "
  INSERT INTO FUNCIONARIO (PERFIL, NOMBRE, PRIMERAPELLIDO, SEGUNDOAPELLIDO, CORREO, CONTRASENA, ESTADO)
  VALUES (1, 'Admin', 'Admin', 'Admin', 'admin@example.com', '<paste_hash_here>', 1);
"
```

Log in with `admin@example.com` / `admin123`.

### Managing the database container

```bash
# Stop
docker compose -f docker-compose.db.yml down

# Stop and wipe data (re-imports schema on next start)
docker compose -f docker-compose.db.yml down -v

# Access MySQL shell
docker compose -f docker-compose.db.yml exec db mysql -u root -proot COMEDOR
```

---

## User Roles

| Role          | Profile ID | Access                    |
|---------------|------------|---------------------------|
| Administrator | 1          | Full system management    |
| Billing Staff | 2          | Meal billing operations   |
| Client        | 3          | Students and teachers     |

## Troubleshooting

### "Connection error" on page load

- Verify MySQL is running (locally or in Docker)
- Check `.env` credentials match your MySQL setup
- Ensure the `COMEDOR` database exists and the schema was imported

### Blank page or 500 error

- Check PHP error logs: `php -S localhost:8080 2>&1`
- Ensure `mysqli` extension is enabled in `php.ini`
- Verify all files are present (especially `Core/`, `Controller/`, `Model/`, `View/`)

### Profile photos not loading

- Ensure `View/assets/profile/` directory exists
- The default photo is `View/assets/profile/default.jpg`
