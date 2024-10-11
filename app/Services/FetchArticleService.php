<?php
namespace App\Services;

use App\Models\Article;
use App\Models\Source;
use App\Services\Contracts\ArticleFetcherInterface;
use App\Services\NewsFetchers\GuardianAPIFetcher;
use App\Services\NewsFetchers\NYTimesAPIFetcher;
use App\Services\NewsFetchers\NewsAPIFetcher;
use Illuminate\Support\Facades\Cache;

class FetchArticleService
{
    /* Define the fetcher sources classes */
    public function getFetchers()
    {
        return [
            NewsAPIFetcher::class,
            GuardianAPIFetcher::class,
            NYTimesAPIFetcher::class
        ];
    }

    // Fetch articles from all sources
    public function fetchArticles()
    {
        foreach ($this->getFetchers() as $fetcher) {
            $fetchResult = (new $fetcher())->fetch();
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
