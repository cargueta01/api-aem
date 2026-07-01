## Funcionalidades implementadas

- CRUD para Companys, Enterprises y Branchs.
- Arquitectura Controller → DTO → Service → Repository.
- Autenticación mediante JWT.
- Documentación con Swagger/OpenAPI.
- Docker Compose con PostgreSQL.
- Pruebas automatizadas.

# API AEM

API REST desarrollada con Laravel para la gestión de infraestructura comercial.

## Tecnologías

- PHP 8.3
- Laravel 13
- PostgreSQL 15
- Docker Compose
- JWT
- Swagger / OpenAPI
- PHPUnit

## Arquitectura

```
Controller → DTO → Service → Repository → Model
```

## Ejecutar el proyecto

```bash
docker compose up --build
```

Si deseas reiniciar completamente la base de datos:

```bash
docker compose down -v
docker compose up --build
```

Durante el inicio, la aplicación ejecuta automáticamente:

- Migraciones
- Seeders
- Generación de la documentación Swagger

## Accesos

| Servicio | URL |
|----------|-----|
| API | http://localhost:8000 |
| Swagger | http://localhost:8000/docs |
| Health Check | http://localhost:8000/up |

## Usuario administrador

Se crea automáticamente durante la ejecución de los seeders.

| Campo | Valor |
|-------|-------|
| Email | `admin@aem.test` |
| Password | `password` |

Estas credenciales pueden modificarse mediante las siguientes variables de entorno:

```env
AEM_ADMIN_NAME=Admin AEM
AEM_ADMIN_EMAIL=admin@aem.test
AEM_ADMIN_PASSWORD=password
```

## Autenticación

La API utiliza JWT.

Obtener un token:

```http
POST /api/v1/auth/login
```

La respuesta devuelve un **Bearer Token** que debe enviarse en el encabezado:

```http
Authorization: Bearer <token>
```

## Base de datos

La solución implementa una estructura jerárquica compuesta por:

- Companys
- Enterprises
- Branchs

Las migraciones incluyen:

- Relaciones entre entidades.
- Llaves foráneas.
- Índices para claves foráneas.
- Índices para campos de estado.
- Índices para `doc_number` y `municipality_codigo`.

Las eliminaciones se realizan mediante desactivación lógica cambiando el estado del registro a `inactive`.

## Documentación

La documentación completa de la API, incluyendo endpoints, parámetros, ejemplos y respuestas, se encuentra disponible en Swagger:

```
http://localhost:8000/docs
```

## Pruebas

Ejecutar las pruebas:

```bash
php artisan test
```

## Comandos útiles

```bash
docker compose exec api-server php artisan route:list
docker compose exec api-server php artisan migrate:status
docker compose exec api-server php artisan l5-swagger:generate
docker compose logs -f api-server
```