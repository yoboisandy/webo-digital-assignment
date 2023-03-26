<?php

namespace Tests\Feature;

use App\Http\Resources\BlogCategoryResource;
use App\Models\BlogCategory;
use Tests\TestCase;

class BlogCategoryTest extends TestCase
{
    /**
     * Test if the blog categories are listed.
     * @return void
     */
    public function test_lists_blog_categories(): void
    {
        BlogCategory::factory()->count(5)->create();

        $response = $this->getJson(route('blog-categories.index'));

        $response->assertStatus(200);

        $this->assertEquals(
            $response->json('data'),
            BlogCategoryResource::collection(BlogCategory::all())
                ->jsonSerialize()
        );
    }

    /**
     * Test if the blog category is stored.
     * @return void
     */
    public function test_stores_blog_category(): void
    {
        $response = $this->postJson(route('blog-categories.store'), [
            'name' => $this->faker->name,
        ]);

        $response->assertStatus(200);

        $this->assertEquals(BlogCategory::count(), 1);

        $this->assertEquals(
            $response->json('data'),
            (new BlogCategoryResource(BlogCategory::first()))->jsonSerialize()
        );
    }

    /**
     * Test if the blog category is shown by id.
     * @return void
     */
    public function test_shows_blog_category_by_id(): void
    {
        $blogCategory = BlogCategory::factory()->create();

        $response = $this->getJson(route('blog-categories.show', $blogCategory->id));

        $response->assertStatus(200);

        $this->assertEquals($response->json('data'), (new BlogCategoryResource($blogCategory))->jsonSerialize());
    }

    /**
     * Test if the blog category is updated.
     * @return void
     */
    public function test_updates_blog_category(): void
    {
        $blogCategory = BlogCategory::factory()->create();

        $response = $this->putJson(route('blog-categories.update', $blogCategory->id), [
            'name' => $this->faker->name,
        ]);

        $response->assertStatus(200);

        $this->assertEquals(BlogCategory::count(), 1);

        $this->assertEquals(
            $response->json('data'),
            (new BlogCategoryResource(BlogCategory::first()))->jsonSerialize()
        );
    }

    /**
     * Test if the blog category is deleted.
     * @return void
     */
    public function test_deletes_blog_category(): void
    {
        $blogCategory = BlogCategory::factory()->create();

        $response = $this->deleteJson(route('blog-categories.destroy', $blogCategory->id));

        $response->assertStatus(200);

        $this->assertEquals(BlogCategory::count(), 0);
    }

    /**
     * Test if the blog category is not shown when accessing non existing category.
     * @return void
     */
    public function test_returns_error_response_when_accessing_non_existing_category()
    {
        $response = $this->getJson(route('blog-categories.show', 1));

        $response->assertStatus(500);

        $response = $this->putJson(route('blog-categories.update', 1), [
            'name' => $this->faker->name,
        ]);

        $response->assertStatus(500);

        $response = $this->deleteJson(route('blog-categories.destroy', 1));

        $response->assertStatus(500);
    }

    /**
     * Test if the blog category is not stored with invalid data.
     * @dataProvider Tests\DataProviders\BlogDataProviders::invalidBlogCategoriesData
     * @param array $invalidData
     * @param array $invalidFields
     * @return void
     */
    public function test_does_not_store_or_update_blog_category_with_invalid_data($invalidData, $invalidFields): void
    {
        $response = $this->postJson(route('blog-categories.store'), $invalidData);

        $response->assertStatus(500);

        $response->assertJsonValidationErrors($invalidFields);

        $this->assertEquals(BlogCategory::count(), 0);

        // for update
        $blogCategory = BlogCategory::factory()->create();

        $response = $this->putJson(route('blog-categories.update', $blogCategory->id), $invalidData);

        $response->assertStatus(500);

        $response->assertJsonValidationErrors($invalidFields);

        $this->assertEquals(BlogCategory::first()->name, $blogCategory->name);
    }
}
