<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogCategoryRequest;
use App\Http\Resources\BlogCategoryResource;
use App\Models\BlogCategory;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    use ApiResponses;

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $blogCategories = BlogCategory::all();

        return $this->successResponse(
            BlogCategoryResource::collection($blogCategories)
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param BlogCategoryRequest $request
     * @return JsonResponse
     */
    public function store(BlogCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();

        $blogCategory = BlogCategory::create($data);

        return $this->successResponse(
            new BlogCategoryResource($blogCategory),
            "Blog category added successfully."
        );
    }

    /**
     * Display the specified resource.
     * @param BlogCategory $blogCategory
     * @return JsonResponse
     */
    public function show(BlogCategory $blogCategory): JsonResponse
    {
        return $this->successResponse(new BlogCategoryResource($blogCategory));
    }

    /**
     * Update the specified resource in storage.
     * @param BlogCategoryRequest $request
     * @param BlogCategory $blogCategory
     * @return JsonResponse
     */
    public function update(BlogCategoryRequest $request, BlogCategory $blogCategory): JsonResponse
    {
        $data = $request->validated();

        $blogCategory->update($data);

        return $this->successResponse(
            new BlogCategoryResource($blogCategory),
            "Blog category updated successfully."
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        return $this->successResponse(
            null,
            "Blog category deleted successfully."
        );
    }
}
