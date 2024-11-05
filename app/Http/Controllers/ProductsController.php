<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Permissions\ProductsPermission;
use App\Services\ProductService;
use App\Traits\Responsable;

class ProductsController extends Controller
{
    use Responsable;

    public function __construct(
        private readonly ProductService $productService,
        private readonly ProductsPermission $permission
    ) {
    }

    /**
     * @OA\Get(
     *    path="/api/v1/products",
     *    tags={"Products"},
     *    summary="Products List API",
     *    description="Products List API",
     *    security={{"bearer":{}}},
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product list fetched successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $this->permission->canViewProducts();

            return $this->successResponse('Product list fetched successfully.', $this->productService->get());
        } catch (\Throwable $th) {
            return $this->errorResponse('Product list could not be fetched.', $th);
        }
    }

    /**
     * @OA\Post(
     *    path="/api/v1/products",
     *    tags={"Products"},
     *    summary="Create Product API",
     *    description="Create Product API",
     *    security={{"bearer":{}}},
     *    @OA\RequestBody(
     *     required=true,
     *     description="Create New product with product data",
     *     @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(
     *         @OA\Property(property="name", type="string", example="Product Name"),
     *         @OA\Property(property="slug", type="string", example="product-name"),
     *         @OA\Property(property="description", type="string", example="Product description"),
     *         @OA\Property(property="price", type="float", example=100),
     *         @OA\Property(property="sale_price", type="float", example=80),
     *         @OA\Property(property="quantity", type="integer", example=1),
     *         @OA\Property(property="brand_id", type="integer", example=2),
     *         @OA\Property(property="category_id", type="integer", example=2),
     *         @OA\Property(property="shop_id", type="integer", example=1),
     *         @OA\Property(property="is_featured", type="integer", example=1),
     *         @OA\Property(property="status", type="string", example="draft"),
     *         @OA\Property(property="total_view", type="integer", example=0),
     *         @OA\Property(property="total_searched", type="integer", example=0),
     *         @OA\Property(property="total_ordered", type="integer", example=0),
     *         @OA\Property(property="created_by", type="integer", example=1),
     *         @OA\Property(property="updated_by", type="integer", example=1),
     *         @OA\Property(property="images", type="string", format="binary", example=null),
     *      )
     *     ),
     *    ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product created successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function store(ProductCreateRequest $request)
    {
        try {
            $this->permission->canCreateProducts();

            return $this->successResponse(
                'Product created successfully.',
                $this->productService->store($request->all())
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('Product could not be created.', $th);
        }
    }

    /**
     * @OA\Get(
     *    path="/api/v1/products/{id}",
     *    tags={"Products"},
     *    summary="Products Detail API",
     *    description="Products Detail API",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(name="id", description="Product ID or Slug", example=1, required=true, in="path", @OA\Schema(type="string")),
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
            $this->permission->canViewProducts();

            return $this->successResponse(
                'Product detail fetched successfully.',
                $this->productService->findById($id)
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('Product detail could not be fetched.', $th);
        }
    }

    /**
     * @OA\Post(
     *    path="/api/v1/products/{id}",
     *    tags={"Products"},
     *    summary="Update product API",
     *    description="Update product API",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(name="id", description="Product ID or Slug", example=1, required=true, in="path", @OA\Schema(type="string")),
     *    @OA\Parameter(name="_method", description="Method", example="PUT", required=true, in="query", @OA\Schema(type="string")),
     *    @OA\RequestBody(
     *     required=true,
     *     description="Update product with product data",
     *     @OA\MediaType(
     *      mediaType="multipart/form-data",
    *      @OA\Schema(
     *         @OA\Property(property="name", type="string", example="Product Name"),
     *         @OA\Property(property="slug", type="string", example="product-name"),
     *         @OA\Property(property="description", type="string", example="Product description"),
     *         @OA\Property(property="price", type="float", example=100),
     *         @OA\Property(property="sale_price", type="float", example=80),
     *         @OA\Property(property="quantity", type="integer", example=1),
     *         @OA\Property(property="brand_id", type="integer", example=2),
     *         @OA\Property(property="category_id", type="integer", example=2),
     *         @OA\Property(property="shop_id", type="integer", example=1),
     *         @OA\Property(property="is_featured", type="integer", example=1),
     *         @OA\Property(property="status", type="string", example="draft"),
     *         @OA\Property(property="total_view", type="integer", example=0),
     *         @OA\Property(property="total_searched", type="integer", example=0),
     *         @OA\Property(property="total_ordered", type="integer", example=0),
     *         @OA\Property(property="created_by", type="integer", example=1),
     *         @OA\Property(property="updated_by", type="integer", example=1),
     *         @OA\Property(property="images", type="string", format="binary", example=null),
     *      )
     *     ),
     *    ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product updated successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        try {
            $this->permission->canUpdateProducts();

            return $this->successResponse(
                'Product updated successfully.',
                $this->productService->update($request->all(), $id)
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('Product could not be updated.', $th);
        }
    }

    /**
     * @OA\DELETE(
     *    path="/api/v1/products/{id}",
     *    tags={"Products"},
     *    summary="Delete product API",
     *    description="Delete product API",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(name="id", description="Product ID or Slug", example=1, required=true, in="path", @OA\Schema(type="string")),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product deleted successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try {
            $this->permission->canDeleteProducts();

            return $this->successResponse(
                'Product deleted successfully.',
                $this->productService->destroy($id)
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('Product could not be deleted.', $th);
        }
    }

    /**
     * @OA\DELETE(
     *    path="/api/v1/products-images/{id}",
     *    tags={"Products"},
     *    summary="Delete product Image API",
     *    description="Delete product Image API",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(name="id", description="Product Image ID", example=1, required=true, in="path", @OA\Schema(type="string")),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product deleted successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function deleteImage(string $id)
    {
        try {
            $this->permission->canDeleteProducts();

            return $this->successResponse(
                'Product images deleted successfully.',
                $this->productService->destroyImage($id)
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('Product could not be deleted.', $th);
        }
    }
}
