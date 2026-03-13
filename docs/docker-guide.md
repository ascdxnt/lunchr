# Docker Guide

Run SCMS entirely inside Docker containers -- no need to install PHP or MySQL on your machine. You only need **Docker** and **Docker Compose**.

## Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (Windows/macOS) or [Docker Engine](https://docs.docker.com/engine/install/) (Linux)
- Docker Compose v2+ (included with Docker Desktop)

Verify your installation:

```bash
docker --version
docker compose version
```

## Quick Start

### 1. Clone and enter the project

```bash
git clone https://github.com/ecx2f/scms.git scms
cd scms
```

### 2. Create your environment file

```bash
cp .env.example .env
```

Edit `.env` with your preferred settings:

```env
DB_HOST=db
DB_USER=root
DB_PASS=root
DB_NAME=COMEDOR

APP_PORT=8080
DB_PORT=3306
```

> **Important:** When using Docker, `DB_HOST` must be set to `db` (the service name in `docker-compose.yml`), not `localhost`.

### 3. Start the containers

```bash
docker compose up -d
```

This will:
1. Build the PHP/Apache container from the `Dockerfile`
2. Pull and start a MySQL 8.0 container
3. Automatically import the database schema from `querys.sql`
4. Wait for MySQL to be healthy before starting the app

### 4. Open the application

Go to: **http://localhost:8080**

(Or whatever port you set in `APP_PORT`)

### 5. Create an initial admin user

On first run the database is empty. Create an admin user:

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

Log in with:
- **Email:** `admin@example.com`
- **Password:** `admin123`

## Container Architecture

```
┌─────────────────────┐     ┌─────────────────────┐
│   scms-app          │     │   scms-db            │
│   PHP 8.5 + Apache  │────>│   MySQL 8.0          │
│   Port: 8080        │     │   Port: 3306         │
│                     │     │                     │
│   Serves the app    │     │   Database: COMEDOR  │
│   at /var/www/html  │     │   Data: db_data vol  │
└─────────────────────┘     └─────────────────────┘
```

| Container  | Image            | Purpose                        |
|------------|------------------|--------------------------------|
| `scms-app` | Custom (PHP 8.5) | Runs PHP with Apache           |
| `scms-db`  | `mysql:8.0`      | MySQL database                 |

## Common Commands

### Start containers (background)

```bash
docker compose up -d
```

### Stop containers

```bash
docker compose down
```

### Stop and remove all data (reset database)

```bash
docker compose down -v
```

> The `-v` flag removes the `db_data` volume, which wipes the database. On the next `up`, the schema will be re-imported from `querys.sql`.

### View logs

```bash
# All containers
docker compose logs -f

# Only the app
docker compose logs -f app

# Only the database
docker compose logs -f db
```

### Rebuild after changes to Dockerfile

```bash
docker compose up -d --build
```

### Run a PHP command inside the container

```bash
docker compose exec app php -v
docker compose exec app php -r "echo 'Hello from container';"
```

### Access MySQL shell

```bash
docker compose exec db mysql -u root -proot COMEDOR
```

### Re-import the database schema

```bash
docker compose down -v
docker compose up -d
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
- Check if the app is waiting for the database: `docker compose logs app`
- The health check ensures the app waits for MySQL, but the first start may take 20-30 seconds

### Database schema not loaded

The schema is only imported on the **first run** (when the `db_data` volume is created). If you need to re-import:

```bash
docker compose down -v
docker compose up -d
```

### Changes to PHP files not reflected

The project directory is mounted as a volume, so file changes are reflected immediately. If you still don't see changes:

```bash
docker compose restart app
```

### Permission issues (Linux)

If you get permission errors on Linux, ensure your user can run Docker:

```bash
sudo usermod -aG docker $USER
```

Then log out and back in.
