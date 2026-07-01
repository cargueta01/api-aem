# API de Gestion de Infraestructura Comercial AEM

API REST en Laravel.

La solucion implementa tres niveles jerarquicos:

- `companys`: holding o consorcio principal.
- `enterprises`: empresas o marcas asociadas a una compania.
- `branchs`: sucursales fisicas asociadas a una empresa.

## Stack Tecnico

- PHP 8.3
- Laravel 13
- PostgreSQL 15
- JWT para autenticacion
- L5 Swagger / OpenAPI para documentacion
- Docker Compose
- PHPUnit para pruebas automatizadas

## Arquitectura

```txt
Controller -> DTO -> Service -> Repository -> Model / Database
```

Responsabilidades principales:

- `Controller`: recibe HTTP, construye DTOs y retorna JSON.
- `DTO`: valida y normaliza payloads de entrada.
- `Service`: contiene reglas de negocio y validaciones complejas.
- `Repository`: aisla consultas y persistencia con Eloquent.
- `Model`: representa entidades y relaciones.

## Servicios Docker

El entorno Docker levanta dos servicios:

- `api-server`: aplicacion Laravel.
- `db-postgres`: base de datos PostgreSQL.

Ambos se comunican por la red interna `aem-network`. Laravel no usa `localhost` para conectarse a PostgreSQL dentro de Docker; usa el host interno:

```env
DB_HOST=db-postgres
```

PostgreSQL no se expone al host por defecto; la API es el unico servicio publicado.

## Levantar el Proyecto

Desde la raiz del proyecto:

```bash
docker compose up --build
```

Si quieres reiniciar la base de datos desde cero:

```bash
docker compose down -v
docker compose up --build
```

Durante el arranque, el contenedor de la API ejecuta automaticamente:

```bash
php artisan config:clear
php artisan migrate --force
php artisan l5-swagger:generate
php artisan serve --host=0.0.0.0 --port=8000
```

## URLs Principales

Aplicacion:

```txt
http://localhost:8000
```

Healthcheck:

```txt
http://localhost:8000/up
```

Swagger UI:

```txt
http://localhost:8000/docs
```

OpenAPI JSON:

```txt
http://localhost:8000/docs-json?api-docs.json
```

## Crear Usuario de Prueba

En otra terminal, con Docker corriendo:

```bash
docker compose exec api-server php artisan tinker
```

Dentro de Tinker:

```php
\App\Models\User::updateOrCreate(
    ['email' => 'admin@aem.test'],
    [
        'name' => 'Admin',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
    ]
);
```

## Autenticacion

Login:

```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@aem.test","password":"password"}'
```

La respuesta devuelve un token JWT:

```json
{
  "access_token": "...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

Usa el token en endpoints protegidos:

```bash
curl http://localhost:8000/api/v1/companys \
  -H "Accept: application/json" \
  -H "Authorization: Bearer TU_TOKEN"
```

## Endpoints Principales

Autenticacion:

```txt
POST /api/v1/auth/login
GET  /api/v1/auth/me
POST /api/v1/auth/logout
POST /api/v1/auth/refresh
```

Companys:

```txt
GET    /api/v1/companys
POST   /api/v1/companys
GET    /api/v1/companys/{id}
PUT    /api/v1/companys/{id}
DELETE /api/v1/companys/{id}
```

Enterprises:

```txt
GET    /api/v1/enterprises
POST   /api/v1/enterprises
GET    /api/v1/enterprises/{id}
PUT    /api/v1/enterprises/{id}
DELETE /api/v1/enterprises/{id}
```

Branchs:

```txt
GET    /api/v1/branchs
POST   /api/v1/branchs
GET    /api/v1/branchs/{id}
PUT    /api/v1/branchs/{id}
DELETE /api/v1/branchs/{id}
```

Filtros obligatorios para sucursales:

```txt
GET /api/v1/branchs?enterprise_id=1
GET /api/v1/branchs?municipality_codigo=0601
GET /api/v1/branchs?enterprise_id=1&municipality_codigo=0601
```

## Ejemplos de Payload

Crear company:

```json
{
  "name": "Grupo AEM",
  "companys_status": "active"
}
```

Crear enterprise:

```json
{
  "company_id": 1,
  "name": "AEM Express",
  "enterprises_status": "active"
}
```

Crear branch:

```json
{
  "enterprise_id": 1,
  "name": "Sucursal Centro",
  "doc_number": "BR-0001",
  "municipality_codigo": "0601",
  "branchs_status": "active"
}
```

## Base de Datos

Las migraciones definen:

- Llaves foraneas entre `companys -> enterprises -> branchs`.
- Indices para llaves foraneas.
- Indices para campos de estado.
- Indices para `doc_number` y `municipality_codigo`.

Reglas de integridad implementadas:

- `enterprises.company_id` referencia `companys.id` con `ON DELETE RESTRICT` y `ON UPDATE CASCADE`.
- `branchs.enterprise_id` referencia `enterprises.id` con `ON DELETE CASCADE` y `ON UPDATE CASCADE`.

El borrado desde la API se maneja como desactivacion segura, cambiando el estado a `inactive`.

## Manejo de Errores

La API retorna errores en JSON y no expone stack traces internos.

Ejemplo:

```json
{
  "message": "Resource not found.",
  "errors": []
}
```

Validacion:

```json
{
  "message": "Validation failed.",
  "errors": {
    "name": ["The name field is required."]
  }
}
```

## Pruebas Automatizadas

Las pruebas usan SQLite en memoria segun `phpunit.xml`.

Para ejecutarlas en el entorno local de desarrollo:

```bash
composer install
php artisan test
```

El Dockerfile de ejecucion instala dependencias de produccion con `--no-dev`, por lo que las pruebas se ejecutan preferiblemente fuera de la imagen de runtime.

## Comandos Utiles

Ver rutas:

```bash
docker compose exec api-server php artisan route:list
```

Regenerar Swagger manualmente:

```bash
docker compose exec api-server php artisan l5-swagger:generate
```

Ver migraciones:

```bash
docker compose exec api-server php artisan migrate:status
```

Ver logs:

```bash
docker compose logs -f api-server
```

## Notas de Nomenclatura

El proyecto mantiene los nombres `companys` y `branchs` porque asi aparecen en el requerimiento tecnico y en el contrato de endpoints solicitado. En los modelos Eloquent se define explicitamente `$table` cuando Laravel pluralizaria de otra forma.
