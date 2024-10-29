<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Permissions\CategoriesPermission;
use App\Services\CategoryService;
use App\Traits\Responsable;

class CategoriesController extends Controller
{
    use Responsable;

    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly CategoriesPermission $permission
    ) {
    }

    /**
     * @OA\Get(
     *    path="/api/v1/categories",
     *    tags={"Categories"},
     *    summary="Categories List API",
     *    description="Categories List API",
     *    security={{"bearer":{}}},
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category list fetched successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $this->permission->canViewCategories();

            return $this->successResponse('Category list fetched successfully.', $this->categoryService->get());
        } catch (\Throwable $th) {
            return $this->errorResponse('Category list could not be fetched.', $th);
        }
    }

    /**
     * @OA\Post(
     *    path="/api/v1/categories",
     *    tags={"Categories"},
     *    summary="Create Category API",
     *    description="Create Category API",
     *    security={{"bearer":{}}},
     *    @OA\RequestBody(
     *     required=true,
     *     description="Create New category with category data",
     *     @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(
     *         @OA\Property(property="name", type="string", example="Category Name"),
     *         @OA\Property(property="slug", type="string", example="category-name"),
     *         @OA\Property(property="description", type="string", example="Category description"),
     *         @OA\Property(property="parent_id", type="int"),
     *         @OA\Property(property="image", type="string", format="binary", example=null),
     *      )
     *     ),
     *    ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category created successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function store(CategoryCreateRequest $request)
    {
        try {
            $this->permission->canCreateCategories();

            return $this->successResponse(
                'Category created successfully.',
                $this->categoryService->store($request->all())
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('Category could not be created.', $th);
        }
    }

    /**
     * @OA\Get(
     *    path="/api/v1/categories/{id}",
     *    tags={"Categories"},
     *    summary="Categories Detail API",
     *    description="Categories Detail API",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(name="id", description="Category ID or Slug", example=1, required=true, in="path", @OA\Schema(type="string")),
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
            $this->permission->canViewCategories();

            return $this->successResponse(
                'Category detail fetched successfully.',
                $this->categoryService->findById($id)
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('Category detail could not be fetched.', $th);
        }
    }

    /**
     * @OA\Post(
     *    path="/api/v1/categories/{id}",
     *    tags={"Categories"},
     *    summary="Update category API",
     *    description="Update category API",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(name="id", description="Category ID or Slug", example=1, required=true, in="path", @OA\Schema(type="string")),
     *    @OA\Parameter(name="_method", description="Method", example="PUT", required=true, in="query", @OA\Schema(type="string")),
     *    @OA\RequestBody(
     *     required=true,
     *     description="Update category with category data",
     *     @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(
     *        required={"name","slug"},
     *        @OA\Property(property="id", type="int", example=1),
     *        @OA\Property(property="name", type="string", example="Category Name"),
     *        @OA\Property(property="slug", type="string", example="category-name"),
     *        @OA\Property(property="description", type="string", example="Category description"),
     *        @OA\Property(property="parent_id", type="int"),
     *        @OA\Property(property="image", type="string", format="binary", example=null),
     *      ),
     *     ),
     *    ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category updated successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            $this->permission->canUpdateCategories();

            return $this->successResponse(
                'Category updated successfully.',
                $this->categoryService->update($request->all(), $id)
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('Category could not be updated.', $th);
        }
    }

    /**
     * @OA\DELETE(
     *    path="/api/v1/categories/{id}",
     *    tags={"Categories"},
     *    summary="Delete category API",
     *    description="Delete category API",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(name="id", description="Category ID or Slug", example=1, required=true, in="path", @OA\Schema(type="string")),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category deleted successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try {
            $this->permission->canDeleteCategories();

            return $this->successResponse(
                'Category deleted successfully.',
                $this->categoryService->destroy($id)
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('Category could not be deleted.', $th);
        }
    }
}
