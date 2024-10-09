<?php

namespace App\Models;

/**
 * @OA\Schema(
 *     schema="Article",
 *     type="object",
 *     title="Article",
 *     description="An article object",
 *     @OA\Property(property="id", type="integer", description="ID of the article"),
 *     @OA\Property(property="title", type="string", description="Title of the article"),
 *     @OA\Property(property="body", type="string", description="Content of the article"),
 *     @OA\Property(property="thumb", type="string", description="Thumb image of the article"),
 *     @OA\Property(property="web_url", type="string", description="Web URL of the article"),
 *     @OA\Property(property="category_id", type="integer", description="Category of the article"),
 *     @OA\Property(property="source_id", type="integer", description="Source of the article"),
 *     @OA\Property(property="news_source", type="string", description="API Source of the article"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Publication date of the article")
 * )
 */

use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'thumb',
        'category_id',
        'web_url',
        'published_at',
        'source_id',
        'news_source'
    ];

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'article_author');
    }
}
