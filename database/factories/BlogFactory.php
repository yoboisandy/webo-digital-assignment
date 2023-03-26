<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Blog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category_id = BlogCategory::factory()->create()->id;
        return [
            'title' => $title = $this->faker->sentence,
            'slug' => Str::slug($title),
            'image' => $this->faker->image(public_path('images/blogs'), 300, 300),
            'content' => $this->faker->paragraph,
            'blog_category_id' => $category_id,
            'status' => $this->faker->randomElement(['draft', 'published']),
        ];
    }
}
