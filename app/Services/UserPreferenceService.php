<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Cache;

class UserPreferenceService
{
    protected $model;

    /**
     * UserPreferenceService constructor.
     * @param UserPreference $model
     */
    public function __construct(UserPreference $model)
    {
        $this->model = $model;
    }

    public function setPreferences($user, $key, array $preferences)
    {
        $this->model->updateOrCreate(
            ['user_id' => $user->id, 'key' => $key],
            ['value' => $preferences]
        );
    }

    public function getAvailablePreferences()
    {
        $oneHour = 60 * 60;
        return [
            'categories' => Cache::remember('categories', $oneHour, function () {
                return Category::orderBy('name', 'asc')->get(['id', 'name']);
            }),

            'authors' => Cache::remember('authors', $oneHour, function () {
                return Author::orderBy('name', 'asc')->get(['id', 'name']);
            }),

            'sources' => Cache::remember('sources', $oneHour, function () {
                return Source::orderBy('name', 'asc')->get(['id', 'name']);
            })
        ];
    }

    public function getPreferences($user)
    {
        return $this->model->where('user_id', $user->id)
                                      ->pluck('value', 'key')
                                      ->toArray();
    }
}