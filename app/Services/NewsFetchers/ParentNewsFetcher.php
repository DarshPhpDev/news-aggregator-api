<?php

namespace App\Services\NewsFetchers;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
class ParentNewsFetcher {

    const NEWS_API_SOURCE_NAME = 'newsapi';
    const THE_GUARDIAN_API_SOURCE_NAME = 'theguardianapi';
    const NEW_YORK_TIMES_API_SOURCE_NAME = 'newyorktimesapi';

    public static function storeArticle($title, $body, $category, $authors, $thumb, $url, $publishedAt, $source = null, $news_source = null){
        DB::beginTransaction();
        try {
            $createdArticle = Article::create([
                'title'         => $title,
                'body'          => $body,
                'category_id'   => $category ? self::findOrCreateCategory($category) : null,
                'thumb'         => $thumb ?? 'https://placehold.co/400x200?text=No+Image',
                'web_url'       => $url,
                'published_at'  => date('Y-m-d H:i:s', strtotime($publishedAt)),
                'source_id'     => $source ? self::findOrCreateSource($source) : null,
                'news_source'   => $news_source
            ]);

            if(!empty($authors)){
                self::findOrCreateAuthors($createdArticle, $authors);
            }

            DB::commit();
        }catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating article from parent fetcher: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function findOrCreateSource($source)
    {
        return Source::firstOrCreate(['name' =>  $source])->id;
    }

    public static function findOrCreateCategory($category)
    {
        return Category::firstOrCreate(['name' =>  $category])->id;
    }

    public static function findOrCreateAuthors($createdArticle, $authors)
    {
        $authorIds = [];
        if(!is_array($authors) && !empty($authors)){
            $authors = explode(',', $authors);
        }
        foreach($authors as $author){
            $authorIds [] = Author::firstOrCreate(['name' =>  $author])->id;
        }
        $createdArticle->authors()->sync($authorIds);
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