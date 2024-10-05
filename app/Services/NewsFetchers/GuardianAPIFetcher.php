<?php

namespace App\Services\NewsFetchers;

use App\Services\Contracts\ArticleFetcherInterface;
use App\Services\NewsFetchers\ParentNewsFetcher;
use Illuminate\Support\Facades\Http;

class GuardianAPIFetcher extends ParentNewsFetcher implements ArticleFetcherInterface
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
            'pageSize'                                  => 20,
            'page'                                      => 1,
        ];
    }

    /**
     * Fetch articles based on api configs.
     *
     * @return array
     */
    public function fetch() : array
    {
        $response = Http::get($this->configs['api_url'], $this->setQueryParams());

        if ($response->ok() && strtolower($response->json('status')) === 'ok') {

            foreach ($response->json('results') as $article) {
                parent::storeArticle(
                    $article['webTitle'],
                    $article['webTitle'],
                    $article['pillarName'] ?? 'General',
                    null,
                    null,
                    $article['webUrl'],
                    $article['webPublicationDate'] ?? now(),
                    null,
                    parent::THE_GUARDIAN_API_SOURCE_NAME
                );
            }
            return parent::successResponse();
            
        }else{
            return parent::failedResponse($response->json());
        }
    }
}
