<?php

namespace App\Services\NewsFetchers;

use App\Models\Article;
use App\Models\Source;

class ParentNewsFetcher {

    const NEWS_API_SOURCE_NAME = 'newsapi';
    const THE_GUARDIAN_API_SOURCE_NAME = 'theguardianapi';
    const NEW_YORK_TIMES_API_SOURCE_NAME = 'newyorktimesapi';

    public static function storeArticle($title, $body, $category, $author, $thumb, $url, $publishedAt, $source = null, $news_source = null){
        Article::create([
            'title'         => $title,
            'body'          => $body,
            'category'      => $category,
            'author'        => $author,
            'thumb'         => $thumb ?? 'https://placehold.co/400x200?text=No+Image',
            'web_url'       => $url,
            'published_at'  => date('Y-m-d H:i:s', strtotime($publishedAt)),
            'source_id'     => $source ? self::findOrCreateSource($source) : null,
            'news_source'   => $news_source
        ]);
    }

    public static function findOrCreateSource($source)
    {
        return Source::firstOrCreate(['name' =>  $source])->id;
    }

    public static function successResponse()
    {
        return [
            'status'        => true,
            'msg'           => 'Fetched successfuly and saved',
        ];
    }

    public static function failedResponse($jsonResponse)
    {
        return [
            'status'    => false,
            'msg'       => isset($jsonResponse['message']) ? $jsonResponse['message'] : 'Internal api server error',
        ];
    }
}