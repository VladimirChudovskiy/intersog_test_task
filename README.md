# Intersog Test Task

This repository contains a Laravel application with Docker setup for easy development.

## Other documentation files
- [Book Parser Documentation](src/documentations/parser.md) - Details on the book parsing functionality.

## API Documentation
- localhost:8000/api/documentation - Swagger/OpenAPI documentation for the API endpoints.

## Prerequisites

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Make](https://www.gnu.org/software/make/) (usually pre-installed on most Unix-based systems)

## Project Setup

### Initial Setup

1. Clone the repository:
   ```bash
   git clone git@github.com:VladimirChudovskiy/intersog_test_task.git
   cd intersog_test_task
   ```

2. Start the Docker containers:
   ```bash
   make up
   ```

3. Install PHP dependencies:
   ```bash
   make install
   ```

4. Run database migrations:
   ```bash
   make migrate
   ```

5. (Optional) Seed the database with sample data:
   ```bash
   make fresh-seed
   ```

### Environment Configuration

The project uses Docker environment variables defined in:
- `docker-compose.yml` - For Docker services configuration
- `src/.env` - For Laravel application configuration

The default database configuration is:
- Host: `db` (MySQL container)
- Database: `laravel`
- Username: `laravel`
- Password: `secret`

Please change these values in `src/.env` for security and production use.

## Development Workflow

### Starting the Application

```bash
make up
```

The application will be available at http://localhost:8000

### Stopping the Application

```bash
make down
```

### Restarting the Application

```bash
make restart
```

### Rebuilding Docker Images

To rebuild Docker images, use:
```bash
make build
```

### Viewing Application Logs

```bash
make logs
```

### Accessing the Application Container Shell

```bash
make bash
```

### Database Operations

Run migrations:
```bash
make migrate
```

Refresh database and run seeders:
```bash
make fresh-seed
```

## Available Make Commands

| Command | Description |
|---------|-------------|
| `make up` | Start Docker containers in detached mode |
| `make down` | Stop and remove Docker containers |
| `make restart` | Restart Docker containers |
| `make install` | Install Composer dependencies |
| `make laravel` | Create a new Laravel project (for initial setup) |
| `make migrate` | Run database migrations |
| `make fresh-seed` | Refresh the database and run seeders |
| `make logs` | Show logs from the app container |
| `make bash` | Open a bash shell in the app container |
| `make build` | Build Docker images for the project |

## Project Structure

- `src/` - Laravel application code
- `Dockerfile` - PHP application container configuration
- `docker-compose.yml` - Docker services configuration
- `Makefile` - Development workflow commands
