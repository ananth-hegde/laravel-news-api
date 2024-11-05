<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Laravel News API",
 *     version="1.0.0",
 *     description="API for aggregating news from multiple sources"
 * )
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Local API Server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *  * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints for user authentication"
 * )
 * @OA\Tag(
 *     name="News Articles",
 *     description="Endpoints for managing news articles"
 * )
 * @OA\Tag(
 *     name="User Preferences",
 *     description="Endpoints for managing user preferences"
 * )
 * @OA\Schema(
 *     schema="NewsArticle",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="News Title"),
 *     @OA\Property(property="url", type="string", example="https://example.com/news"),
 *     @OA\Property(property="source", type="string", example="News Source"),
 *     @OA\Property(property="category", type="string", example="general"),
 *     @OA\Property(property="author", type="string", example="Author Name"),
 *     @OA\Property(property="published_at", type="string", format="date-time", example="2023-10-01T00:00:00.000000Z")
 * )
 */
class ApiController extends Controller
{
}