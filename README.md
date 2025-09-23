# PHP + Nginx + MySQL + phpMyAdmin

Simple Docker Compose setup for a PHP application served by Nginx with MySQL and phpMyAdmin for database administration.

## Requirements
- Docker 20.10+
- Docker Compose v2 plugin

## Getting Started
1. Copy or clone this project.
2. Start the stack:
   ```sh
   docker compose up --build -d
   ```
3. Visit the app at http://localhost:8880
4. Access phpMyAdmin at http://localhost:8881 using:
   - Server: `db`
   - Username: `app_user`
   - Password: `app_pass`

## Services
- `nginx`: Serves static assets and proxies PHP requests to `php`.
- `php`: PHP-FPM container based on `php:8.2-fpm-alpine` with PDO MySQL support.
- `db`: MySQL 8.0 with default credentials defined in `docker-compose.yml`.
- `phpmyadmin`: Web UI for managing the MySQL database.

## Development Notes
- Application code lives in `src/` and is mounted into the PHP and Nginx containers.
- Update `docker/php/php.ini` or extend the Dockerfile to add more PHP extensions.
- Database files persist in the `db_data` Docker volume.