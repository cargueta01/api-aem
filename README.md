# API AEM

API REST desarrollada con Laravel para la gestión de infraestructura comercial.

## Tecnologías

- PHP 8.3
- Laravel 13
- PostgreSQL 15
- Docker Compose
- JWT
- Swagger (OpenAPI)
- PHPUnit

## Arquitectura

```
Controller → DTO → Service → Repository → Model
```

## Ejecutar el proyecto

```bash
docker compose up --build
```

La aplicación estará disponible en:

- API: http://localhost:8000
- Swagger: http://localhost:8000/docs
- Health Check: http://localhost:8000/up

## Base de datos

El proyecto utiliza PostgreSQL con Docker.

Las migraciones incluyen:

- Relaciones entre `companys`, `enterprises` y `branchs`.
- Llaves foráneas.
- Índices para claves foráneas.
- Índices para estados.
- Índices para `doc_number` y `municipality_codigo`.

## Autenticación

La API utiliza JWT.

Para obtener un token:

```
POST /api/v1/auth/login
```

La documentación completa de los endpoints está disponible en Swagger.

## Pruebas

Ejecutar:

```bash
php artisan test
```