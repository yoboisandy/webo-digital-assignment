<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Support\Str;

class BlogTest extends TestCase
{
    /**
     * Test if the blog is stored and updated
     * @return void
     */
    public function test_stores_and_updates_blog(): void
    {
        $blogCategory = BlogCategory::factory()->create();
        $response = $this->postJson(route('blogs.storeOrUpdate'), [
            'title' => $title = $this->faker->sentence,
            'slug' => Str::slug($title),
            'image' => UploadedFile::fake()->image(time() . '.jpg'),
            'content' => $this->faker->paragraph,
            'blog_category_id' => $blogCategory->id,
            'status' => $this->faker->randomElement(['draft', 'published']),
        ]);

        $response->assertOk();

        $blog = Blog::first();

        $this->assertEquals(
            $response->json('data.title'),
            $blog->title
        );

        $this->assertEquals(Blog::count(), 1);

        // Update the blog
        $response = $this->postJson(route('blogs.storeOrUpdate'), [
            'id' => $blog->id,
            'title' => $title = $this->faker->sentence,
            'slug' => Str::slug($title),
            'image' => UploadedFile::fake()->image(time() . '.jpg'),
            'content' => $this->faker->paragraph,
            'blog_category_id' => $blogCategory->id,
            'status' => $this->faker->randomElement(['draft', 'published']),
        ]);

        $response->assertOk();

        $this->assertEquals($blog->fresh()->title, $title);
    }

    /**
     * Test if all blogs are listed.
     * @return void
     */
    public function test_lists_all_published_blogs(): void
    {
        Blog::factory()->count(5)->create(
            [
                'status' => 'published'
            ]
        );

        $response = $this->getJson(route('blogs.index'));

        $response->assertOk();

        $this->assertEquals(
            count($response->json('data')),
            Blog::count()
        );
    }

    /**
     * Test if the blog is shown by slug.
     * @return void
     */
    public function test_shows_blog_by_slug(): void
    {
        $blog = Blog::factory()->create();

        $response = $this->getJson(route('blogs.show', $blog->slug));

        $response->assertOk();

        $this->assertEquals(
            $response->json('data.title'),
            $blog->title
        );
    }

    /**
     * Test if the blog is deleted.
     * @return void
     */
    public function test_deletes_blog(): void
    {
        $blog = Blog::factory()->create();

        $response = $this->deleteJson(route('blogs.destroy', $blog->id));

        $response->assertOk();

        $this->assertEquals(Blog::count(), 0);
    }

    /**
     * Test if the blog is shown by category.
     * @return void
     */
    public function test_shows_blog_by_category(): void
    {
        $blogCategory = BlogCategory::factory()->create();

        Blog::factory()->count(5)->create(
            [
                'blog_category_id' => $blogCategory->id,
                'status' => 'published'
            ]
        );

        $response = $this->getJson(route('blogs.blogsByCategory', $blogCategory->id));

        $response->assertOk();

        $this->assertEquals(
            count($response->json('data')),
            Blog::where('blog_category_id', $blogCategory->id)->count()
        );
    }

    /**
     * Test if the blog is not stored and updated with invalid data.
     * @dataProvider Tests\DataProviders\BlogDataProviders::invalidBlogData
     * @param array $invalidData
     * @param array $invalidFields
     * @return void
     */
    public function test_does_not_store_and_update_blog_with_invalid_data($invalidData, $invalidFields): void
    {
        BlogCategory::factory()->create();
        $response = $this->postJson(route('blogs.storeOrUpdate'), $invalidData);

        $response->assertStatus(500);

        $response->assertJsonValidationErrors($invalidFields);

        $this->assertEquals(Blog::count(), 0);
    }
}
