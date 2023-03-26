<?php

namespace Tests\DataProviders;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class BlogDataProviders
{
    /**
     * Invalid blog categories data
     * @return array
     */
    public function invalidBlogCategoriesData(): array
    {
        return [
            "name is required" => [
                [
                    'name' => '',
                ],
                ['name']
            ],
            "name should be string" => [
                [
                    'name' => 123,
                ],
                ['name']
            ],
            "name should be max 255 characters" => [
                [
                    'name' => Str::random(256),
                ],
                ['name']
            ]
        ];
    }

    /**
     * Invalid blog data provider.
     * @return array
     */
    public function invalidBlogData(): array
    {
        $title = "Sample Title";
        $content = "Sample Content";
        $status = "published";
        return [
            "id does not exists" => [
                [
                    'id' => 1,
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'image' => UploadedFile::fake()->image(time() . '.jpg'),
                    'content' => $content,
                    'blog_category_id' => 1,
                    'status' => "published",
                ],
                ['id']
            ],
            "title is required" => [
                [
                    'title' => "",
                    'slug' => "sample-slug",
                    'image' => UploadedFile::fake()->image(time() . '.jpg'),
                    'content' => $content,
                    'blog_category_id' => 1,
                    'status' => "published",
                ],
                ['title']
            ],
            "title shoudl be string" => [
                [
                    'title' => 123,
                    'slug' => "sample-slug",
                    'image' => UploadedFile::fake()->image(time() . '.jpg'),
                    'content' => $content,
                    'blog_category_id' => 1,
                    'status' => "published",
                ],
                ['title']
            ],
            "title should be less than 255 characters" => [
                [
                    'title' => Str::random(256),
                    'slug' => Str::slug($title),
                    'image' => UploadedFile::fake()->image(time() . '.jpg'),
                    'content' => $content,
                    'blog_category_id' => 1,
                    'status' => "published",
                ],
                ['title']
            ],
            "slug is required" => [
                [
                    'title' => $title,
                    'slug' => "",
                    'image' => UploadedFile::fake()->image(time() . '.jpg'),
                    'content' => $content,
                    'blog_category_id' => 1,
                    'status' => $status,
                ],
                ['slug']
            ],
            "slug should be string" => [
                [
                    'title' => $title,
                    'slug' => 1,
                    'image' => UploadedFile::fake()->image(time() . '.jpg'),
                    'content' => $content,
                    'blog_category_id' => 1,
                    'status' => $status,
                ],
                ['slug']
            ],
            "slug should be less than 255 characters" => [
                [
                    'title' => $title,
                    'slug' => Str::slug(Str::random(256)),
                    'image' => UploadedFile::fake()->image(time() . '.jpg'),
                    'content' => $content,
                    'blog_category_id' => 1,
                    'status' => $status,
                ],
                ['slug']
            ],
            "image is required" => [
                [
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'image' => '',
                    'content' => $content,
                    'blog_category_id' => 1,
                    'status' => $status,
                ],
                ['image']
            ],
            "image mime type should be image/jpeg, image/png, image/jpg" => [
                [
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'image' => UploadedFile::fake()->create('document.pdf'),
                    'content' => $content,
                    'blog_category_id' => 1,
                    'status' => $status,
                ],
                ['image']
            ],
            "image should be less than 2MB" => [
                [
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'image' => UploadedFile::fake()->image(time() . '.jpg')->size(3000),
                    'content' => $content,
                    'blog_category_id' => 1,
                    'status' => $status,
                ],
                ['image']
            ],
            "content is required" => [
                [
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'image' => UploadedFile::fake()->image(time() . '.jpg'),
                    'content' => '',
                    'blog_category_id' => 1,
                    'status' => $status,
                ],
                ['content']
            ],
            "blog category id is required" => [
                [
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'image' => UploadedFile::fake()->image(time() . '.jpg'),
                    'content' => $content,
                    'blog_category_id' => '',
                    'status' => $status,
                ],
                ['blog_category_id']
            ],
            "blog category id should exists in blog categories table" => [
                [
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'image' => UploadedFile::fake()->image(time() . '.jpg'),
                    'content' => $content,
                    'blog_category_id' => rand(1000, 10000),
                    'status' => $status,
                ],
                ['blog_category_id']
            ],
            "status is required" => [
                [
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'image' => UploadedFile::fake()->image(time() . '.jpg'),
                    'content' => $content,
                    'blog_category_id' => 1,
                    'status' => '',
                ],
                ['status']
            ],
            "status should be draft or published" => [
                [
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'image' => UploadedFile::fake()->image(time() . '.jpg'),
                    'content' => $content,
                    'blog_category_id' => 1,
                    'status' => 'invalid',
                ],
                ['status']
            ],
        ];
    }
}
