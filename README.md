# API AEM

API REST desarrollada con Laravel para la gestion de infraestructura comercial.

## Tecnologias

- PHP 8.3
- Laravel 13
- PostgreSQL 15
- Docker Compose
- JWT
- Swagger / OpenAPI
- PHPUnit

## Arquitectura

```txt
Controller -> DTO -> Service -> Repository -> Model
```

## Ejecutar el Proyecto

```bash
docker compose up --build
```

Si necesitas reiniciar la base de datos desde cero:

```bash
docker compose down -v
docker compose up --build
```

Durante el arranque, el contenedor ejecuta automaticamente:

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan l5-swagger:generate
php artisan serve --host=0.0.0.0 --port=8000
```

La aplicacion estara disponible en:

- API: http://localhost:8000
- Swagger: http://localhost:8000/docs
- Health Check: http://localhost:8000/up

## Usuario Inicial

El proyecto crea automaticamente un usuario administrador con el seeder principal.

Credenciales por defecto:

```txt
Email: admin@aem.test
Password: password
```

Estas credenciales pueden modificarse desde variables de entorno antes de levantar Docker:

```env
AEM_ADMIN_NAME="Admin AEM"
AEM_ADMIN_EMAIL=admin@aem.test
AEM_ADMIN_PASSWORD=password
```

## Autenticacion

La API utiliza JWT.

Para obtener un token:

```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@aem.test","password":"password"}'
```

Respuesta esperada:

```json
{
  "access_token": "...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

Usar el token en endpoints protegidos:

```bash
curl http://localhost:8000/api/v1/companys \
  -H "Accept: application/json" \
  -H "Authorization: Bearer TU_TOKEN"
```

## Base de Datos

El proyecto utiliza PostgreSQL con Docker. La API se conecta a la base de datos mediante la red interna de Docker:

```env
DB_HOST=db-postgres
```

Las migraciones incluyen:

- Relaciones entre `companys`, `enterprises` y `branchs`.
- Llaves foraneas.
- Indices para claves foraneas.
- Indices para estados.
- Indices para `doc_number` y `municipality_codigo`.

El borrado desde la API se maneja como desactivacion segura, cambiando el estado a `inactive`.

## Endpoints Principales

```txt
POST /api/v1/auth/login
GET  /api/v1/auth/me
POST /api/v1/auth/logout
POST /api/v1/auth/refresh

GET    /api/v1/companys
POST   /api/v1/companys
GET    /api/v1/companys/{id}
PUT    /api/v1/companys/{id}
DELETE /api/v1/companys/{id}

GET    /api/v1/enterprises
POST   /api/v1/enterprises
GET    /api/v1/enterprises/{id}
PUT    /api/v1/enterprises/{id}
DELETE /api/v1/enterprises/{id}

GET    /api/v1/branchs
POST   /api/v1/branchs
GET    /api/v1/branchs/{id}
PUT    /api/v1/branchs/{id}
DELETE /api/v1/branchs/{id}
```

Filtros de sucursales:

```txt
GET /api/v1/branchs?enterprise_id=1
GET /api/v1/branchs?municipality_codigo=0601
GET /api/v1/branchs?enterprise_id=1&municipality_codigo=0601
```

## Pruebas

Ejecutar en el entorno local de desarrollo:

```bash
composer install
php artisan test
```

## Comandos Utiles

```bash
docker compose exec api-server php artisan route:list
docker compose exec api-server php artisan migrate:status
docker compose exec api-server php artisan l5-swagger:generate
docker compose logs -f api-server
```
