<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NewsArticle>
 */
class NewsArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'title' => $this->faker->sentence,
            'url' => $this->faker->url,
            'source' => $this->faker->randomElement(['BBC', 'CNN', 'Fox News', 'The Guardian', 'The New York Times', 'The Wall Street Journal']),
            'category' => $this->faker->randomElement(['Politics', 'Business', 'Technology', 'Entertainment', 'Sports', 'General']),
            'author' => $this->faker->name,
            'published_at' => $this->faker->dateTimeThisDecade(),

        ];
    }
}
