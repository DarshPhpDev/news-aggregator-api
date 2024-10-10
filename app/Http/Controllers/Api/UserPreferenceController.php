<?php

namespace App\Http\Controllers\Api;

use ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserPreferenceRequest;
use App\Services\UserPreferenceService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="User Preferences", description="Set and get user preferences")
 */


class UserPreferenceController extends Controller
{
    protected $userPreferenceService;

    public function __construct(UserPreferenceService $userPreferenceService)
    {
        $this->userPreferenceService = $userPreferenceService;
    }

    /**
     * @OA\Get(
     *     path="/api/available-preferences",
     *     summary="Get available preferences (sources, categories and authors)",
     *     description="Fetch a list of available preferences such as sources, categories, and authors.",
     *     tags={"User Preferences"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with available preferences",
     *         @OA\JsonContent(
     *             @OA\Property(property="sources", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="categories", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="authors", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function getAvailablePreferences()
    {
        return ApiResponse::sendResponse($this->userPreferenceService->getAvailablePreferences(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/preferences",
     *     summary="Set user preferences",
     *     description="Save user preferences such as sources, categories, and authors.",
     *     tags={"User Preferences"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="sources", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="categories", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="authors", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Preferences updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Preferences updated successfully.")
     *         )
     *     )
     * )
     */

    public function setPreferences(UserPreferenceRequest $request)
    {
        $user = \Auth::user();

        $validated = $request->validated();

        // Save preferences for 'sources'
        if (isset($validated['sources']) && !empty($validated['sources'])) {
            $this->userPreferenceService->setPreferences($user, 'sources', $validated['sources']);
        }

        // Save preferences for 'categories'
        if (isset($validated['categories']) && !empty($validated['categories'])) {
            $this->userPreferenceService->setPreferences($user, 'categories', $validated['categories']);
        }

        // Save preferences for 'authors'
        if (isset($validated['authors']) && !empty($validated['authors'])) {
            $this->userPreferenceService->setPreferences($user, 'authors', $validated['authors']);
        }
        return ApiResponse::sendResponse([], 200, 'Preferences updated successfully.');
    }


    /**
     * @OA\Get(
     *     path="/api/preferences",
     *     summary="Get user preferences",
     *     description="Retrieve the user's saved preferences for sources, categories, and authors.",
     *     tags={"User Preferences"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with user preferences",
     *         @OA\JsonContent(
     *             @OA\Property(property="sources", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="categories", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="authors", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    
    public function getPreferences()
    {
        $user = \Auth::user();
        return ApiResponse::sendResponse($this->userPreferenceService->getPreferences($user), 200);
    }
}
