<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserPreferenceService;
use Illuminate\Http\Request;
use ApiResponse;

class UserPreferenceController extends Controller
{
    protected $userPreferenceService;

    public function __construct(UserPreferenceService $userPreferenceService)
    {
        $this->userPreferenceService = $userPreferenceService;
    }

    public function getAvailablePreferences()
    {
        return ApiResponse::sendResponse($this->userPreferenceService->getAvailablePreferences(), 200);
    }

    public function setPreferences(Request $request)
    {
        $user = \Auth::user();
        // Validate the request input
        $validated = $request->validate([
            'sources' => 'array',
            'categories' => 'array',
            'authors' => 'array',
        ]);

        // Save preferences for 'sources'
        if (!empty($validated['sources'])) {
            $this->userPreferenceService->setPreferences($user, 'sources', $validated['sources']);
        }

        // Save preferences for 'categories'
        if (!empty($validated['categories'])) {
            $this->userPreferenceService->setPreferences($user, 'categories', $validated['categories']);
        }

        // Save preferences for 'authors'
        if (!empty($validated['authors'])) {
            $this->userPreferenceService->setPreferences($user, 'authors', $validated['authors']);
        }
        return ApiResponse::sendResponse([], 200, 'Preferences updated successfully.');
    }

    public function getPreferences()
    {
        return ApiResponse::sendResponse($this->userPreferenceService->getPreferences(), 200);
    }
}
