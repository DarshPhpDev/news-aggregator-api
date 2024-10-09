<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class ArticleService
{
    // Fetch articles with optional filters and pagination
    public function getArticles(array $filters = [], int $perPage = 10)
    {
        $query = Article::query();

        $this->cleanFilters($filters);

        // Apply filters
        if (!empty($filters['keyword'])) {
            $query->where('title', 'LIKE', "%{$filters['keyword']}%")
                  ->orWhere('body', 'LIKE', "%{$filters['keyword']}%");
        }

        if (!empty($filters['category'])) {
            $query->whereHas('source', function ($q) use ($filters){
                $q->whereIn('sources.id', $filters['source']);
            });
        }

        if (!empty($filters['author'])) {
            $query->whereIn('author', $filters['author']);
        }

        if (!empty($filters['source'])) {
            $query->whereHas('source', function ($q) use ($filters){
                $q->whereIn('sources.id', $filters['source']);
            });
        }

        return $query->paginate($perPage);
    }

    // Fetch a single article by ID
    public function getArticleById(int $id)
    {
        return Article::findOrFail($id);
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
}
