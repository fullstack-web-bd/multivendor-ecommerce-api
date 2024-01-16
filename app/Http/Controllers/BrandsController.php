<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandCreateRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Services\BrandService;
use App\Traits\Responsable;

class BrandsController extends Controller
{
    use Responsable;

    public function __construct(
        private readonly BrandService $brandService
    ) {
    }

    /**
     * @OA\Get(
     *    path="/api/v1/brands",
     *    tags={"Brands"},
     *    summary="Brands List API",
     *    description="Brands List API",
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Brand list fetched successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            return $this->successResponse('Brand list fetched successfully.', $this->brandService->get());
        } catch (\Throwable $th) {
            return $this->errorResponse('Brand list could not be fetched.', $th);
        }
    }

    /**
     * @OA\Post(
     *    path="/api/v1/brands",
     *    tags={"Brands"},
     *    summary="Create Brand API",
     *    description="Create Brand API",
     *    @OA\RequestBody(
     *     required=true,
     *     description="Create New brand with brand data",
     *     @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(
     *         @OA\Property(property="name", type="string", example="Brand Name"),
     *         @OA\Property(property="slug", type="string", example="brand-name"),
     *         @OA\Property(property="description", type="string", example="Brand description"),
     *         @OA\Property(property="image", type="string", format="binary", example=null),
     *      )
     *     ),
     *    ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Brand created successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function store(BrandCreateRequest $request)
    {
        try {
            return $this->successResponse(
                'Brand created successfully.',
                $this->brandService->store($request->all())
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('Brand could not be created.', $th);
        }
    }

    /**
     * @OA\Get(
     *    path="/api/v1/brands/{id}",
     *    tags={"Brands"},
     *    summary="Brands Detail API",
     *    description="Brands Detail API",
     *    @OA\Parameter(name="id", description="Brand ID or Slug", example=1, required=true, in="path", @OA\Schema(type="string")),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Welcome to Ecommerce Multi Vendor API"),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        try {
            return $this->successResponse(
                'Brand detail fetched successfully.',
                $this->brandService->findById($id)
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('Brand detail could not be fetched.', $th);
        }
    }

    /**
     * @OA\Post(
     *    path="/api/v1/brands/{id}",
     *    tags={"Brands"},
     *    summary="Update brand API",
     *    description="Update brand API",
     *    @OA\Parameter(name="id", description="Brand ID or Slug", example=1, required=true, in="path", @OA\Schema(type="string")),
     *    @OA\Parameter(name="_method", description="Method", example="PUT", required=true, in="query", @OA\Schema(type="string")),
     *    @OA\RequestBody(
     *     required=true,
     *     description="Update brand with brand data",
     *     @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(
     *        required={"name","slug"},
     *        @OA\Property(property="id", type="int", example=1),
     *        @OA\Property(property="name", type="string", example="Brand Name"),
     *        @OA\Property(property="slug", type="string", example="brand-name"),
     *        @OA\Property(property="description", type="string", example="Brand description"),
     *        @OA\Property(property="image", type="string", format="binary", example=null),
     *      ),
     *     ),
     *    ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Brand updated successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function update(BrandUpdateRequest $request, $id)
    {
        try {
            return $this->successResponse(
                'Brand updated successfully.',
                $this->brandService->update($request->all(), $id)
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('Brand could not be updated.', $th);
        }
    }

    /**
     * @OA\DELETE(
     *    path="/api/v1/brands/{id}",
     *    tags={"Brands"},
     *    summary="Delete brand API",
     *    description="Delete brand API",
     *    @OA\Parameter(name="id", description="Brand ID or Slug", example=1, required=true, in="path", @OA\Schema(type="string")),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Brand deleted successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try {
            return $this->successResponse(
                'Brand deleted successfully.',
                $this->brandService->destroy($id)
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('Brand could not be deleted.', $th);
        }
    }
}
