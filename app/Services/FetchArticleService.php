<?php
namespace App\Services;

use App\Models\Article;
use App\Models\Source;
use App\Services\Contracts\ArticleFetcherInterface;

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
    }
}
