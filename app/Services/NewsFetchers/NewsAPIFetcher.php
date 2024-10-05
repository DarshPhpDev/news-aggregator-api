<?php

namespace App\Services\NewsFetchers;

use App\Services\Contracts\ArticleFetcherInterface;
use App\Services\NewsFetchers\ParentNewsFetcher;
use Illuminate\Support\Facades\Http;

class NewsAPIFetcher extends ParentNewsFetcher implements ArticleFetcherInterface
{
    protected $configs;

    public function __construct()
    {
        $this->configs = config('news-sources.newsapi');
    }

    public function setQueryParams()
    {
        return [
            $this->configs['api_parameter_name']        => $this->configs['api_key'],
            'language'                                  => 'en'
        ];
    }

    /**
     * Fetch articles based on api configs.
     *
     * @return array
     */
    public function fetch(): array
    {
        $response = Http::get($this->configs['api_url'], $this->setQueryParams());

        if ($response->ok() && strtolower($response->json('status')) === 'ok') {

            foreach ($response->json('articles') as $article) {
                parent::storeArticle(
                    $article['title'],
                    $article['content'],
                    $article['category'] ?? 'General',
                    $article['author'] ?? null,
                    $article['urlToImage'] ?? null,
                    $article['url'],
                    $article['published_at'] ?? now(),
                    isset($article['source']['name']) && !empty($article['source']['name']) 
                        ? $article['source']['name'] 
                        : null,
                    parent::NEWS_API_SOURCE_NAME
                );
            }
            return parent::successResponse();
            
        }else{
            return parent::failedResponse($response->json());
        }
    }
}
