<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {
    }

    /**
     * @OA\Get(
     *    path="/api/v1/categories",
     *    tags={"Categories"},
     *    summary="Categories List API",
     *    description="Categories List API",
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
    public function index()
    {
        return $this->categoryService->get();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * @OA\Get(
     *    path="/api/v1/categories/{id}",
     *    tags={"Categories"},
     *    summary="Categories Detail API",
     *    description="Categories Detail API",
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
        return $this->categoryService->findById($id);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
