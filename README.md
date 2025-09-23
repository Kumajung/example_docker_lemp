# PHP + Nginx + MySQL + phpMyAdmin

Simple Docker Compose setup for a PHP application served by Nginx with MySQL and phpMyAdmin for database administration.

## Requirements
- Docker 20.10+
- Docker Compose v2 plugin

## Getting Started (Local)
1. Copy or clone this project.
2. Optionally copy `.env.example` to `.env` and adjust ports or credentials.
3. Start the stack:
   ```sh
   docker compose up --build -d
   ```
4. Visit the app at http://localhost:${HTTP_PORT:-8080}
5. Access phpMyAdmin at http://localhost:${PHPMYADMIN_PORT:-8081} using:
   - Server: `db`
   - Username: `${MYSQL_USER:-app_user}`
   - Password: `${MYSQL_PASSWORD:-app_pass}`

## Deploying with Portainer
- Point Portainer to this repository when creating a new stack.
- Set environment variables in the stack editor if you need different ports or credentials. Values fall back to those in `.env.example`.
- Portainer clones the repository onto the Docker host. The default bind mounts (`APP_SOURCE` and `NGINX_CONF_PATH`) will therefore resolve automatically. If you prefer using a named volume for application code, set `APP_SOURCE=app_code` in the stack environment variables.
- After deploying, Portainer will build the PHP image directly on the target host using the included Dockerfile.

## Services
- `nginx`: Serves static assets and proxies PHP requests to `php`.
- `php`: PHP-FPM container based on `php:8.2-fpm-alpine` with PDO MySQL support. The build stage copies the application code into the image.
- `db`: MySQL 8.0 (configurable) with default credentials defined via environment variables.
- `phpmyadmin`: Web UI for managing the MySQL database.

## Development Notes
- Application code lives in `src/` and is mounted into the PHP and Nginx containers by default. Override `APP_SOURCE` in `.env` or the stack settings if required.
- Update `docker/php/php.ini` or extend the Dockerfile to add more PHP extensions.
- Database files persist in the `db_data` Docker volume.