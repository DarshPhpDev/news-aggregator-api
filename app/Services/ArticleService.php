<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Source;
use App\Services\UserPreferenceService;

class ArticleService
{
    protected $model;
    protected $userPreferenceService;

    /**
     * ArticleService constructor.
     * @param Article $model
     */
    public function __construct(Article $model, UserPreferenceService $userPreferenceService)
    {
        $this->model = $model;
    }

    // Fetch articles with optional filters and pagination
    public function getArticles(array $filters = [], int $perPage = 10)
    {
        $query = $this->model->query();

        $this->cleanFilters($filters);

        // Apply filters
        if (!empty($filters['keyword'])) {
            $query->where('title', 'LIKE', "%{$filters['keyword']}%")
                  ->orWhere('body', 'LIKE', "%{$filters['keyword']}%");
        }

        if (!empty($filters['category'])) {
            $query->whereHas('category', function ($q) use ($filters){
                $q->whereIn('categories.id', $filters['category']);
            });
        }

        if (!empty($filters['author'])) {
            $query->whereHas('authors', function ($q) use ($filters){
                $q->whereIn('authors.id', $filters['author']);
            });
        }

        if (!empty($filters['source'])) {
            $query->whereHas('source', function ($q) use ($filters){
                $q->whereIn('sources.id', $filters['source']);
            });
        }

        /*
            If authenticated user, then filter the articles by his prefered preferences
        */
        if($user = Auth::check()){
            $query = $this->FilterArticlesByUserPreferredPreferences($user, $query);
        }

        return $query->paginate($perPage);
    }

    // Fetch a single article by ID
    public function getArticleById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function cleanFilters(&$filters)
    {
        if (!empty($filters['keyword'])) {
            $filters['keyword'] = htmlspecialchars(strip_tags(trim($filters['keyword'])));
        }

        // Convert category, source, author to arrays if comma-separated
        $otherFields = ['category', 'source', 'author'];
        foreach ($otherFields as $field) {
            if (!empty($filters[$field])) {
                $filters[$field] = explode(',', trim($filters[$field]));
            }
        }
    }

    public function FilterArticlesByUserPreferredPreferences($user, $query)
    {
        $preferences = $this->userPreferenceService->getPreferences($user);

        $query->when(!empty($preferences['sources']), function($q) use ($preferences) {
            $sourceIds = Source::whereIn('name', $preferences['sources'])->pluck('id');
            $q->whereIn('source_id', $sourceIds);
        });

        $query->when(!empty($preferences['categories']), function($q) use ($preferences) {
            $categoryIds = Category::whereIn('name', $preferences['categories'])->pluck('id');
            $q->whereIn('category_id', $categoryIds);
        });
        
        $query->when(!empty($preferences['authors']), function($q) use ($preferences) {
            $q->whereHas('authors', function ($q) use ($preferences) {
                $q->whereIn('name', $preferences['authors']);
            });
        });

        return $query;
    }
}
