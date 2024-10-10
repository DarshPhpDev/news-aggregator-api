<?php
namespace App\Services;

use App\Models\Article;
use App\Models\Source;
use App\Services\Contracts\ArticleFetcherInterface;
use Illuminate\Support\Facades\Cache;

class FetchArticleService
{
    protected $fetchers = [];

    // Inject multiple fetchers through the constructor
    public function __construct(array $fetchers)
    {
        $this->fetchers = $fetchers;
    }

    // Fetch articles from all sources
    public function fetchArticles()
    {
        foreach ($this->fetchers as $fetcher) {
            $fetchResult = $fetcher->fetch();
        }
        $this->clearCachedResources();
    }

    public function clearCachedResources()
    {
        Cache::forget('categories');
        Cache::forget('authors');
        Cache::forget('sources');
    }
}
