<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Category\StoreCategoryRequest;
use App\Http\Requests\Api\V1\Category\UpdateCategoryRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return ApiResponse::successResponse(CategoryResource::collection($categories), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return ApiResponse::successResponse(new CategoryResource($category), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return ApiResponse::successResponse(new CategoryResource($category), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return ApiResponse::successResponse(new CategoryResource($category), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return ApiResponse::successResponse(new CategoryResource($category), 201);
    }

    public function children(Category $category)
    {
        return ApiResponse::successResponse(new CategoryResource($category->load('children')), 200);
    }
    public function parent(Category $category)
    {
        return ApiResponse::successResponse(new CategoryResource($category->load('parent')), 200);
    }

    public function products(Category $category)
    {
        return ApiResponse::successResponse(new CategoryResource($category->load('products')), 200);
    }
}
