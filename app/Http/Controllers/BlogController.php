<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Models\BlogCategory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    /**
     * Display all blogs
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $blogs = Blog::where('status', 'published')
            ->latest()
            ->get();

        return $this->successResponse(
            BlogResource::collection($blogs)
        );
    }

    /**
     * Add a new blog
     * @param BlogRequest $request
     * @return JsonResponse
     */
    public function storeOrUpdate(BlogRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                if ($request->id) {
                    $blog = Blog::find($request->id);
                    if ($blog->image) {
                        unlink(public_path('images/blogs/' . basename($blog->image)));
                    }
                }

                $image = $request->file('image');
                $image_name = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/blogs'), $image_name);
                $data['image'] = config('app.url') . '/images/blogs/' . $image_name;
            }

            $blog = Blog::updateOrCreate(
                ['id' => $request->id ?? null],
                $data
            );

            $message = $request->id ? 'Blog updated successfully.' : 'Blog added successfully.';

            return $this->successResponse(
                new BlogResource($blog),
                $message
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Display a blog
     * @param string $slug
     * @return JsonResponse
     */
    public function show(string $slug): JsonResponse
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        return $this->successResponse(
            new BlogResource($blog)
        );
    }

    /**
     * Delete a blog
     * @param Blog $blog
     * @return JsonResponse
     */
    public function destroy(Blog $blog): JsonResponse
    {
        try {
            if ($blog->image) {
                unlink(public_path('images/blogs/' . basename($blog->image)));
            }

            $blog->delete();

            return $this->successResponse(
                null,
                'Blog deleted successfully.'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Display blogs by category
     * @param BlogCategory $blogCategory
     * @return JsonResponse
     */
    public function showByCategory(BlogCategory $blogCategory): JsonResponse
    {
        $blogs = Blog::where('blog_category_id', $blogCategory->id)
            ->where('status', 'published')
            ->latest()
            ->get();

        return $this->successResponse(
            BlogResource::collection($blogs)
        );
    }
}
