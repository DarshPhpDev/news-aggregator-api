<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use ApiResponse;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    // Fetch paginated articles
    public function index(Request $request)
    {
        $filters = [
            'keyword' => $request->query('keyword'),
            'category' => $request->query('category'),
            'source' => $request->query('source'),
            'date' => $request->query('date'),
        ];
        return ApiResponse::sendResponse($this->articleService->getArticles($filters), 200);
    }

    // Fetch a single article by ID
    public function show($id)
    {
        return ApiResponse::sendResponse($this->articleService->getArticleById($id), 200);
    }
}
