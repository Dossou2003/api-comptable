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
 *
 * @OA\Schema(
 *     schema="Utilisateur",
 *     title="Utilisateur",
 *     description="Modèle d'utilisateur",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="nom", type="string", example="Dupont"),
 *     @OA\Property(property="prenom", type="string", example="Jean"),
 *     @OA\Property(property="email", type="string", format="email", example="jean.dupont@example.com"),
 *     @OA\Property(property="telephone", type="string", example="+33612345678"),
 *     @OA\Property(property="adresse", type="string", example="123 Rue de Paris"),
 *     @OA\Property(property="role", type="string", enum={"admin", "comptable", "utilisateur"}, example="comptable"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class SwaggerController extends Controller
{
    // Cette classe est utilisée uniquement pour la documentation Swagger
}
