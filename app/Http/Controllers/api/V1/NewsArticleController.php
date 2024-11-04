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

class NewsArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new NewsArticleQueryFilter($request);
        $queryItems = $filter->transform($request);
        return new NewsArticleCollection(NewsArticle::where($queryItems)->paginate());
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
