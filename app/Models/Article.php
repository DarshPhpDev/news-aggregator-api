<?php

namespace App\Models;

use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'category',
        'author',
        'thumb',
        'web_url',
        'published_at',
        'source_id',
    ];

    public function source()
    {
        return $this->belongsTo(Source::class);
    }
}
