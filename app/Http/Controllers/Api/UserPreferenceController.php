<?php

namespace App\Http\Controllers\Api;

use ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserPreferenceRequest;
use App\Services\UserPreferenceService;
use Illuminate\Http\Request;

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

    public function getPreferences()
    {
        $user = \Auth::user();
        return ApiResponse::sendResponse($this->userPreferenceService->getPreferences($user), 200);
    }
}
