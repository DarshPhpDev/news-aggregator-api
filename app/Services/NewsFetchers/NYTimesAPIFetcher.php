<?php

namespace App\Services\NewsFetchers;

use App\Services\Contracts\ArticleFetcherInterface;
use App\Services\NewsFetchers\ParentNewsFetcher;
use Illuminate\Support\Facades\Http;

class NYTimesAPIFetcher extends ParentNewsFetcher implements ArticleFetcherInterface
{
    protected $configs;

    public function __construct()
    {
        $this->configs = config('news-sources.newyorktimesapi');
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

            foreach ($response->json('response')['docs'] as $article) {
                parent::storeArticle(
                    $article['abstract'],
                    $article['lead_paragraph'],
                    $article['section_name'] ?? 'General',
                    isset($article['byline']) ? $this->extractAuthors($article['byline']) : [],
                    isset($article['multimedia']) && count($article['multimedia']) > 0 ? $this->extractImageUrlFromMultimedia($article['multimedia']) : null,
                    $article['web_url'],
                    $article['pub_date'] ?? now(),
                    isset($article['source']) && !empty($article['source']) 
                        ? $article['source'] 
                        : null,
                    parent::NEW_YORK_TIMES_API_SOURCE_NAME
                );
            }
            return parent::successResponse();

        }else{
            return parent::failedResponse($response->json());
        }
    }


    /**
     * Custom function to format the article image url.
     *
     * @return string
     */
    public function extractImageUrlFromMultimedia($multimediaArray)
    {
        return isset($multimediaArray[0]['url']) ? 'https://www.nytimes.com/' . $multimediaArray[0]['url'] : '';
    }

    /**
     * Custom function to extract the article author'/s.
     *
     * @param array
     * @return array
     */
    public function extractAuthors($authorsArr)
    {
        if(isset($authorsArr['person']) && is_array($authorsArr['person'])){
            return array_map(function($person) {
                $firstname = !empty($person['firstname']) ? $person['firstname'] : '';
                $lastname = !empty($person['lastname']) ? $person['lastname'] : '';
                
                return trim($firstname . ' ' . $lastname);
            }, $authorsArr['person']);
        }
        return [];
    }
}
