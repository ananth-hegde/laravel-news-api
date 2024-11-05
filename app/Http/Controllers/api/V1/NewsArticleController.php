<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Resources\V1\NewsArticleCollection;
use App\Http\Resources\V1\NewsArticleResource;
use App\Models\NewsArticle;
use App\Http\Requests\StoreNewsArticleRequest;
use App\Filters\V1\NewsArticleQueryFilter;
use App\Http\Requests\UpdateNewsArticleRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     path="/v1/news-articles",
 *     summary="Get all news articles",
 *     tags={"News Articles"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="title[like]",
 *         in="query",
 *         description="Filter title using LIKE operator (use % for wildcards)",
 *         required=false,
 *         @OA\Schema(type="string", example="%news%")
 *     ),
 *     @OA\Parameter(
 *         name="title[eq]",
 *         in="query",
 *         description="Filter title using exact match",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="category[eq]",
 *         in="query",
 *         description="Filter by exact category",
 *         required=false,
 *         @OA\Schema(type="string", enum={"general", "business", "technology", "sports", "science", "health"})
 *     ),
 *     @OA\Parameter(
 *         name="source[eq]",
 *         in="query",
 *         description="Filter by exact source",
 *         required=false,
 *         @OA\Schema(type="string", example="NY Times")
 *     ),
 *     @OA\Parameter(
 *         name="author[like]",
 *         in="query",
 *         description="Filter author using LIKE operator",
 *         required=false,
 *         @OA\Schema(type="string", example="%John%")
 *     ),
 *     @OA\Parameter(
 *         name="published_at[gt]",
 *         in="query",
 *         description="Filter articles published after date",
 *         required=false,
 *         @OA\Schema(type="string", format="date-time")
 *     ),
 *     @OA\Parameter(
 *         name="published_at[lt]",
 *         in="query",
 *         description="Filter articles published before date",
 *         required=false,
 *         @OA\Schema(type="string", format="date-time")
 *     ),
 *     @OA\Response(
 *         response=200, 
 *         description="List of news articles",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/NewsArticle")),
 *             @OA\Property(property="links", type="object"),
 *             @OA\Property(property="meta", type="object")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthenticated")
 * )
 * 
 * @OA\Get(
 *     path="/v1/news-articles/{id}",
 *     summary="Get a specific news article",
 *     tags={"News Articles"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="News article ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="News article details",
 *         @OA\JsonContent(ref="#/components/schemas/NewsArticle")
 *     ),
 *     @OA\Response(response=404, description="News article not found")
 * )
 */
class NewsArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new NewsArticleQueryFilter($request);
        $queryItems = $filter->transform($request);
        $newsArticles = NewsArticle::where($queryItems)->paginate(10);
        return new NewsArticleCollection($newsArticles->appends($request->query()));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsArticleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(NewsArticle $newsArticle)
    {
        return new NewsArticleResource($newsArticle);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NewsArticle $newsArticle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsArticleRequest $request, NewsArticle $newsArticle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsArticle $newsArticle)
    {
        //
    }
}
