<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     title="API de Gestion Comptable",
 *     version="1.0.0",
 *     description="API pour la gestion comptable avec journal des écritures et génération de balance comptable",
 *     @OA\Contact(
 *         email="contact@example.com",
 *         name="Support API"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="/api",
 *     description="Serveur API"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class SwaggerController extends Controller
{
    // Cette classe est utilisée uniquement pour la documentation Swagger
}
