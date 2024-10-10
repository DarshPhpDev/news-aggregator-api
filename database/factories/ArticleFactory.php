<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title'         => $this->faker->sentence,
            'body'          => $this->faker->paragraph,
            'thumb'         => $this->faker->imageUrl(),
            'web_url'       => $this->faker->url,
            'category_id'   => Category::factory(),
            'source_id'     => Source::factory(),
            'published_at'  => $this->faker->dateTime,
            'news_source'   => 'NewsAPI',
        ];
    }
}
