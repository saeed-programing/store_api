<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Brand\StoreBrandRequest;
use App\Http\Requests\Api\V1\Brand\UpdateBrandRequest;
use App\Http\Resources\Api\V1\BrandResource;
use App\Models\Brand;
use App\Support\ApiResponse;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();
        return ApiResponse::successResponse(new BrandResource($brands), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $brand = Brand::create($request->validated());

        return ApiResponse::successResponse(new BrandResource($brand), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return ApiResponse::successResponse(new BrandResource($brand), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand->update($request->validated());

        return ApiResponse::successResponse(new BrandResource($brand), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return ApiResponse::successResponse(new BrandResource($brand), 201);
    }

    public function products(Brand $brand)
    {
        return ApiResponse::successResponse(new BrandResource($brand->load('products')), 200);

    }
}
