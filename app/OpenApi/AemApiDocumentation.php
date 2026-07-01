<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="AEM Infrastructure API",
 *     version="1.0.0",
 *     description="REST API for managing companys, enterprises and branchs using Controller-Service-Repository architecture."
 * )
 * @OA\Server(url="http://localhost:8000", description="Local server")
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * @OA\Tag(name="Auth", description="Authentication endpoints")
 * @OA\Tag(name="Companys", description="Holding or consortium management")
 * @OA\Tag(name="Enterprises", description="Associated enterprise management")
 * @OA\Tag(name="Branchs", description="Branch management and filters")
 *
 * @OA\Schema(
 *     schema="LoginRequest",
 *     required={"email", "password"},
 *     @OA\Property(property="email", type="string", format="email", example="admin@aem.test"),
 *     @OA\Property(property="password", type="string", example="password")
 * )
 * @OA\Schema(
 *     schema="TokenResponse",
 *     @OA\Property(property="access_token", type="string"),
 *     @OA\Property(property="token_type", type="string", example="bearer"),
 *     @OA\Property(property="expires_in", type="integer", example=3600)
 * )
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     @OA\Property(property="message", type="string", example="Resource not found."),
 *     @OA\Property(property="errors", type="object")
 * )
 * @OA\Schema(
 *     schema="CompanyPayload",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", example="Grupo AEM"),
 *     @OA\Property(property="companys_status", type="string", enum={"active", "inactive"}, example="active")
 * )
 * @OA\Schema(
 *     schema="Company",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Grupo AEM"),
 *     @OA\Property(property="companys_status", type="string", enum={"active", "inactive"}, example="active"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * @OA\Schema(
 *     schema="EnterprisePayload",
 *     required={"company_id", "name"},
 *     @OA\Property(property="company_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="AEM Express"),
 *     @OA\Property(property="enterprises_status", type="string", enum={"active", "inactive"}, example="active")
 * )
 * @OA\Schema(
 *     schema="Enterprise",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="company_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="AEM Express"),
 *     @OA\Property(property="enterprises_status", type="string", enum={"active", "inactive"}, example="active"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * @OA\Schema(
 *     schema="BranchPayload",
 *     required={"enterprise_id", "name", "doc_number", "municipality_codigo"},
 *     @OA\Property(property="enterprise_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Sucursal Centro"),
 *     @OA\Property(property="doc_number", type="string", example="BR-0001"),
 *     @OA\Property(property="municipality_codigo", type="string", example="0601"),
 *     @OA\Property(property="branchs_status", type="string", enum={"active", "inactive"}, example="active")
 * )
 * @OA\Schema(
 *     schema="Branch",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="enterprise_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Sucursal Centro"),
 *     @OA\Property(property="doc_number", type="string", example="BR-0001"),
 *     @OA\Property(property="municipality_codigo", type="string", example="0601"),
 *     @OA\Property(property="branchs_status", type="string", enum={"active", "inactive"}, example="active"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Post(
 *     path="/api/v1/auth/login",
 *     tags={"Auth"},
 *     summary="Authenticate user and return JWT token",
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/LoginRequest")),
 *     @OA\Response(response=200, description="Authenticated", @OA\JsonContent(ref="#/components/schemas/TokenResponse")),
 *     @OA\Response(response=401, description="Invalid credentials", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *     @OA\Response(response=422, description="Validation failed", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
 * )
 * @OA\Get(
 *     path="/api/v1/companys",
 *     tags={"Companys"},
 *     security={{"bearerAuth":{}}},
 *     summary="List companys with pagination",
 *     @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer", minimum=1, maximum=100, example=15)),
 *     @OA\Response(response=200, description="Company list"),
 *     @OA\Response(response=401, description="Unauthenticated", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
 * )
 * @OA\Post(
 *     path="/api/v1/companys",
 *     tags={"Companys"},
 *     security={{"bearerAuth":{}}},
 *     summary="Create company",
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/CompanyPayload")),
 *     @OA\Response(response=201, description="Company created", @OA\JsonContent(ref="#/components/schemas/Company")),
 *     @OA\Response(response=422, description="Validation failed", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
 * )
 * @OA\Get(
 *     path="/api/v1/companys/{id}",
 *     tags={"Companys"},
 *     security={{"bearerAuth":{}}},
 *     summary="Get company by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Company detail", @OA\JsonContent(ref="#/components/schemas/Company")),
 *     @OA\Response(response=404, description="Not found", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
 * )
 * @OA\Put(
 *     path="/api/v1/companys/{id}",
 *     tags={"Companys"},
 *     security={{"bearerAuth":{}}},
 *     summary="Update company",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/CompanyPayload")),
 *     @OA\Response(response=200, description="Company updated", @OA\JsonContent(ref="#/components/schemas/Company")),
 *     @OA\Response(response=404, description="Not found", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
 * )
 * @OA\Delete(
 *     path="/api/v1/companys/{id}",
 *     tags={"Companys"},
 *     security={{"bearerAuth":{}}},
 *     summary="Safely deactivate company",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Company deactivated"),
 *     @OA\Response(response=404, description="Not found", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
 * )
 *
 * @OA\Get(path="/api/v1/enterprises", tags={"Enterprises"}, security={{"bearerAuth":{}}}, summary="List enterprises", @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer", example=15)), @OA\Response(response=200, description="Enterprise list"))
 * @OA\Post(path="/api/v1/enterprises", tags={"Enterprises"}, security={{"bearerAuth":{}}}, summary="Create enterprise", @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/EnterprisePayload")), @OA\Response(response=201, description="Enterprise created", @OA\JsonContent(ref="#/components/schemas/Enterprise")), @OA\Response(response=422, description="Validation failed", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")))
 * @OA\Get(path="/api/v1/enterprises/{id}", tags={"Enterprises"}, security={{"bearerAuth":{}}}, summary="Get enterprise by ID", @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=200, description="Enterprise detail", @OA\JsonContent(ref="#/components/schemas/Enterprise")), @OA\Response(response=404, description="Not found", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")))
 * @OA\Put(path="/api/v1/enterprises/{id}", tags={"Enterprises"}, security={{"bearerAuth":{}}}, summary="Update enterprise", @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/EnterprisePayload")), @OA\Response(response=200, description="Enterprise updated", @OA\JsonContent(ref="#/components/schemas/Enterprise")))
 * @OA\Delete(path="/api/v1/enterprises/{id}", tags={"Enterprises"}, security={{"bearerAuth":{}}}, summary="Safely deactivate enterprise", @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=200, description="Enterprise deactivated"))
 *
 * @OA\Get(
 *     path="/api/v1/branchs",
 *     tags={"Branchs"},
 *     security={{"bearerAuth":{}}},
 *     summary="List branchs with advanced filters",
 *     @OA\Parameter(name="enterprise_id", in="query", required=false, @OA\Schema(type="integer", example=1)),
 *     @OA\Parameter(name="municipality_codigo", in="query", required=false, @OA\Schema(type="string", example="0601")),
 *     @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer", example=15)),
 *     @OA\Response(response=200, description="Branch list")
 * )
 * @OA\Post(path="/api/v1/branchs", tags={"Branchs"}, security={{"bearerAuth":{}}}, summary="Create branch", @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/BranchPayload")), @OA\Response(response=201, description="Branch created", @OA\JsonContent(ref="#/components/schemas/Branch")), @OA\Response(response=422, description="Validation failed", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")))
 * @OA\Get(path="/api/v1/branchs/{id}", tags={"Branchs"}, security={{"bearerAuth":{}}}, summary="Get branch by ID", @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=200, description="Branch detail", @OA\JsonContent(ref="#/components/schemas/Branch")), @OA\Response(response=404, description="Not found", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")))
 * @OA\Put(path="/api/v1/branchs/{id}", tags={"Branchs"}, security={{"bearerAuth":{}}}, summary="Update branch", @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/BranchPayload")), @OA\Response(response=200, description="Branch updated", @OA\JsonContent(ref="#/components/schemas/Branch")))
 * @OA\Delete(path="/api/v1/branchs/{id}", tags={"Branchs"}, security={{"bearerAuth":{}}}, summary="Safely deactivate branch", @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=200, description="Branch deactivated"))
 */
class AemApiDocumentation
{
}
