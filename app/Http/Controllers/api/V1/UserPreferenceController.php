<?php

namespace App\Http\Controllers\api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\NewsArticleCollection;
use App\Models\NewsArticle;
/**
 * @OA\Get(
 *     path="/v1/preferences",
 *     summary="Get user preferences",
 *     tags={"User Preferences"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description="User preferences"),
 *     @OA\Response(response=401, description="Unauthenticated")
 * )
 * 
 * @OA\Put(
 *     path="/v1/preferences",
 *     summary="Update user preferences",
 *     tags={"User Preferences"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="categories", type="array", @OA\Items(type="string")),
 *             @OA\Property(property="sources", type="array", @OA\Items(type="string")),
 *             @OA\Property(property="authors", type="array", @OA\Items(type="string"))
 *         )
 *     ),
 *     @OA\Response(response=200, description="Preferences updated successfully"),
 *     @OA\Response(response=401, description="Unauthenticated")
 * )
 * 
 * @OA\Get(
 *     path="/v1/personalizedFeed",
 *     summary="Get personalized news feed",
 *     tags={"User Preferences"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description="Personalized news feed"),
 *     @OA\Response(response=401, description="Unauthenticated")
 * )
 */
class UserPreferenceController extends Controller
{

    public function personalizedFeed(Request $request)
    {
        $preferences = $request->user()->preferences;
        
        if (!$preferences) {
            return new NewsArticleCollection(NewsArticle::paginate());
        }

        $query = NewsArticle::query();

        if (!empty($preferences->categories)) {
            $query->whereIn('category', $preferences->categories);
        }

        if (!empty($preferences->sources)) {
            $query->whereIn('source', $preferences->sources);
        }

        if (!empty($preferences->authors)) {
            $query->whereIn('author', $preferences->authors);
        }

        return new NewsArticleCollection($query->latest('published_at')->paginate());
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'categories' => 'nullable|array',
            'sources' => 'nullable|array',
            'authors' => 'nullable|array'
        ]);

        // Ensure all keys exist even if not provided
        $preferences = array_merge([
            'categories' => [],
            'sources' => [],
            'authors' => []
        ], $validated);

        $userPreference = $request->user()->preferences()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $preferences
        );

        return response()->json($userPreference);
    }

    public function show(Request $request)
    {
        return response()->json($request->user()->preferences);
    }
}
