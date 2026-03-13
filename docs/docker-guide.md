# Docker Guide

Run SCMS entirely inside Docker containers. No PHP or MySQL installation required -- only **Docker** and **Docker Compose**.

If you prefer to run PHP locally, see [Getting Started](./getting-started.md) instead.

## Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (Windows/macOS) or [Docker Engine](https://docs.docker.com/engine/install/) (Linux)
- Docker Compose v2+ (included with Docker Desktop)

Verify:

```bash
docker --version
docker compose version
```

## 1. Clone the repository

```bash
git clone https://github.com/ecx2f/scms.git scms
cd scms
```

## 2. Configure environment

```bash
cp .env.example .env
```

Edit `.env`:

```env
DB_HOST=db
DB_USER=root
DB_PASS=root
DB_NAME=COMEDOR

APP_PORT=8080
DB_PORT=3306
```

> **Important:** `DB_HOST` must be `db` (the service name in `docker-compose.yml`), not `localhost`.

## 3. Start the containers

```bash
docker compose up -d
```

This will:
1. Build the PHP 8.5 / Apache container from the `Dockerfile`
2. Pull and start a MySQL 8.0 container
3. Import the database schema from `querys.sql` on first run
4. Wait for MySQL to be healthy before starting the app

## 4. Open the application

Go to: **http://localhost:8080**

(Or whatever port you set in `APP_PORT`)

## 5. Create an admin user

On first run the database is empty. Create an admin:

```bash
# Generate a password hash
docker compose exec app php -r "echo password_hash('admin123', PASSWORD_DEFAULT) . PHP_EOL;"
```

Copy the hash, then insert the admin:

```bash
docker compose exec db mysql -u root -proot COMEDOR -e "
  INSERT INTO FUNCIONARIO (PERFIL, NOMBRE, PRIMERAPELLIDO, SEGUNDOAPELLIDO, CORREO, CONTRASENA, ESTADO)
  VALUES (1, 'Admin', 'Admin', 'Admin', 'admin@example.com', '<paste_hash_here>', 1);
"
```

Log in with `admin@example.com` / `admin123`.

## Container Architecture

```
┌─────────────────────┐     ┌─────────────────────┐
│   scms-app          │     │   scms-db            │
│   PHP 8.5 + Apache  │────>│   MySQL 8.0          │
│   Port: 8080        │     │   Port: 3306         │
│                     │     │                      │
│   Serves the app    │     │   Database: COMEDOR   │
│   at /var/www/html  │     │   Data: db_data vol   │
└─────────────────────┘     └─────────────────────┘
```

| Container  | Image            | Purpose              |
|------------|------------------|----------------------|
| `scms-app` | Custom (PHP 8.5) | Runs PHP with Apache |
| `scms-db`  | `mysql:8.0`      | MySQL database       |

## Common Commands

```bash
# Start containers
docker compose up -d

# Stop containers
docker compose down

# Stop and wipe database (re-imports schema on next start)
docker compose down -v

# View logs (all / app only / db only)
docker compose logs -f
docker compose logs -f app
docker compose logs -f db

# Rebuild after Dockerfile changes
docker compose up -d --build

# Run PHP inside the container
docker compose exec app php -v

# Access MySQL shell
docker compose exec db mysql -u root -proot COMEDOR
```

## Customizing Ports

If port 8080 or 3306 is already in use, change them in `.env`:

```env
APP_PORT=9090
DB_PORT=3307
```

Then restart:

```bash
docker compose down
docker compose up -d
```

## Troubleshooting

### "Connection refused" when opening the app

- Make sure both containers are running: `docker compose ps`
- Check logs: `docker compose logs app`
- The first start may take 20-30 seconds while MySQL initializes

### Database schema not loaded

The schema is only imported on the **first run** (when the `db_data` volume is created). To re-import:

```bash
docker compose down -v
docker compose up -d
```

### Changes to PHP files not reflected

The project directory is mounted as a volume, so file changes should be reflected immediately. If not:

```bash
docker compose restart app
```

### Permission issues (Linux)

```bash
sudo usermod -aG docker $USER
```

Then log out and back in.
