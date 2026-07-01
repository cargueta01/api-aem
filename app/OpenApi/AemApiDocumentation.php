<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'AEM Infrastructure API',
    description: 'REST API for managing companys, enterprises and branchs using Controller-Service-Repository architecture.'
)]
#[OA\Server(url: 'http://localhost:8000', description: 'Local server')]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
#[OA\Tag(name: 'Auth', description: 'Authentication endpoints')]
#[OA\Tag(name: 'Companys', description: 'Holding or consortium management')]
#[OA\Tag(name: 'Enterprises', description: 'Associated enterprise management')]
#[OA\Tag(name: 'Branchs', description: 'Branch management and filters')]
#[OA\Schema(
    schema: 'LoginRequest',
    required: ['email', 'password'],
    properties: [
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@aem.test'),
        new OA\Property(property: 'password', type: 'string', example: 'password'),
    ]
)]
#[OA\Schema(
    schema: 'TokenResponse',
    properties: [
        new OA\Property(property: 'access_token', type: 'string'),
        new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
        new OA\Property(property: 'expires_in', type: 'integer', example: 3600),
    ]
)]
#[OA\Schema(
    schema: 'ErrorResponse',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Resource not found.'),
        new OA\Property(property: 'errors', type: 'object'),
    ]
)]
#[OA\Schema(
    schema: 'CompanyPayload',
    required: ['name'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Grupo AEM'),
        new OA\Property(property: 'companys_status', type: 'string', enum: ['active', 'inactive'], example: 'active'),
    ]
)]
#[OA\Schema(
    schema: 'Company',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Grupo AEM'),
        new OA\Property(property: 'companys_status', type: 'string', enum: ['active', 'inactive'], example: 'active'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
#[OA\Schema(
    schema: 'EnterprisePayload',
    required: ['company_id', 'name'],
    properties: [
        new OA\Property(property: 'company_id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'AEM Express'),
        new OA\Property(property: 'enterprises_status', type: 'string', enum: ['active', 'inactive'], example: 'active'),
    ]
)]
#[OA\Schema(
    schema: 'Enterprise',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'company_id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'AEM Express'),
        new OA\Property(property: 'enterprises_status', type: 'string', enum: ['active', 'inactive'], example: 'active'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
#[OA\Schema(
    schema: 'BranchPayload',
    required: ['enterprise_id', 'name', 'doc_number', 'municipality_codigo'],
    properties: [
        new OA\Property(property: 'enterprise_id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Sucursal Centro'),
        new OA\Property(property: 'doc_number', type: 'string', example: 'BR-0001'),
        new OA\Property(property: 'municipality_codigo', type: 'string', example: '0601'),
        new OA\Property(property: 'branchs_status', type: 'string', enum: ['active', 'inactive'], example: 'active'),
    ]
)]
#[OA\Schema(
    schema: 'Branch',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'enterprise_id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Sucursal Centro'),
        new OA\Property(property: 'doc_number', type: 'string', example: 'BR-0001'),
        new OA\Property(property: 'municipality_codigo', type: 'string', example: '0601'),
        new OA\Property(property: 'branchs_status', type: 'string', enum: ['active', 'inactive'], example: 'active'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
#[OA\Post(
    path: '/api/v1/auth/login',
    summary: 'Authenticate user and return JWT token',
    tags: ['Auth'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/LoginRequest')
    ),
    responses: [
        new OA\Response(response: 200, description: 'Authenticated', content: new OA\JsonContent(ref: '#/components/schemas/TokenResponse')),
        new OA\Response(response: 401, description: 'Invalid credentials', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        new OA\Response(response: 422, description: 'Validation failed', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
    ]
)]
#[OA\Get(
    path: '/api/v1/companys',
    summary: 'List companys with pagination',
    security: [['bearerAuth' => []]],
    tags: ['Companys'],
    parameters: [
        new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100, example: 15)),
    ],
    responses: [
        new OA\Response(response: 200, description: 'Company list'),
        new OA\Response(response: 401, description: 'Unauthenticated', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
    ]
)]
#[OA\Post(
    path: '/api/v1/companys',
    summary: 'Create company',
    security: [['bearerAuth' => []]],
    tags: ['Companys'],
    requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/CompanyPayload')),
    responses: [
        new OA\Response(response: 201, description: 'Company created', content: new OA\JsonContent(ref: '#/components/schemas/Company')),
        new OA\Response(response: 422, description: 'Validation failed', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
    ]
)]
#[OA\Get(
    path: '/api/v1/companys/{id}',
    summary: 'Get company by ID',
    security: [['bearerAuth' => []]],
    tags: ['Companys'],
    parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
    responses: [
        new OA\Response(response: 200, description: 'Company detail', content: new OA\JsonContent(ref: '#/components/schemas/Company')),
        new OA\Response(response: 404, description: 'Not found', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
    ]
)]
#[OA\Put(
    path: '/api/v1/companys/{id}',
    summary: 'Update company',
    security: [['bearerAuth' => []]],
    tags: ['Companys'],
    parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
    requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/CompanyPayload')),
    responses: [new OA\Response(response: 200, description: 'Company updated', content: new OA\JsonContent(ref: '#/components/schemas/Company'))]
)]
#[OA\Delete(
    path: '/api/v1/companys/{id}',
    summary: 'Safely deactivate company',
    security: [['bearerAuth' => []]],
    tags: ['Companys'],
    parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
    responses: [new OA\Response(response: 200, description: 'Company deactivated')]
)]
#[OA\Get(path: '/api/v1/enterprises', summary: 'List enterprises', security: [['bearerAuth' => []]], tags: ['Enterprises'], parameters: [new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 15))], responses: [new OA\Response(response: 200, description: 'Enterprise list')])]
#[OA\Post(path: '/api/v1/enterprises', summary: 'Create enterprise', security: [['bearerAuth' => []]], tags: ['Enterprises'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/EnterprisePayload')), responses: [new OA\Response(response: 201, description: 'Enterprise created', content: new OA\JsonContent(ref: '#/components/schemas/Enterprise')), new OA\Response(response: 422, description: 'Validation failed', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse'))])]
#[OA\Get(path: '/api/v1/enterprises/{id}', summary: 'Get enterprise by ID', security: [['bearerAuth' => []]], tags: ['Enterprises'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Enterprise detail', content: new OA\JsonContent(ref: '#/components/schemas/Enterprise')), new OA\Response(response: 404, description: 'Not found', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse'))])]
#[OA\Put(path: '/api/v1/enterprises/{id}', summary: 'Update enterprise', security: [['bearerAuth' => []]], tags: ['Enterprises'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/EnterprisePayload')), responses: [new OA\Response(response: 200, description: 'Enterprise updated', content: new OA\JsonContent(ref: '#/components/schemas/Enterprise'))])]
#[OA\Delete(path: '/api/v1/enterprises/{id}', summary: 'Safely deactivate enterprise', security: [['bearerAuth' => []]], tags: ['Enterprises'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Enterprise deactivated')])]
#[OA\Get(
    path: '/api/v1/branchs',
    summary: 'List branchs with advanced filters',
    security: [['bearerAuth' => []]],
    tags: ['Branchs'],
    parameters: [
        new OA\Parameter(name: 'enterprise_id', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1)),
        new OA\Parameter(name: 'municipality_codigo', in: 'query', required: false, schema: new OA\Schema(type: 'string', example: '0601')),
        new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 15)),
    ],
    responses: [new OA\Response(response: 200, description: 'Branch list')]
)]
#[OA\Post(path: '/api/v1/branchs', summary: 'Create branch', security: [['bearerAuth' => []]], tags: ['Branchs'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/BranchPayload')), responses: [new OA\Response(response: 201, description: 'Branch created', content: new OA\JsonContent(ref: '#/components/schemas/Branch')), new OA\Response(response: 422, description: 'Validation failed', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse'))])]
#[OA\Get(path: '/api/v1/branchs/{id}', summary: 'Get branch by ID', security: [['bearerAuth' => []]], tags: ['Branchs'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Branch detail', content: new OA\JsonContent(ref: '#/components/schemas/Branch')), new OA\Response(response: 404, description: 'Not found', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse'))])]
#[OA\Put(path: '/api/v1/branchs/{id}', summary: 'Update branch', security: [['bearerAuth' => []]], tags: ['Branchs'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/BranchPayload')), responses: [new OA\Response(response: 200, description: 'Branch updated', content: new OA\JsonContent(ref: '#/components/schemas/Branch'))])]
#[OA\Delete(path: '/api/v1/branchs/{id}', summary: 'Safely deactivate branch', security: [['bearerAuth' => []]], tags: ['Branchs'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Branch deactivated')])]
class AemApiDocumentation
{
}
