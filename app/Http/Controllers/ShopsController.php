<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Permissions\CategoriesPermission;
use App\Permissions\ShopsPermission;
use App\Services\CategoryService;
use App\Services\ShopService;
use App\Traits\Responsable;

class ShopsController extends Controller
{
    use Responsable;

    public function __construct(
        private readonly ShopService $shopService,
        private readonly ShopsPermission $permission
    ) {
    }

    /**
     * @OA\Get(
     *    path="/api/v1/shops/dropdown/data",
     *    tags={"Shops"},
     *    summary="Shops Dropdown List",
     *    description="Shops Dropdown List",
     *    security={{"bearer":{}}},
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Shop list fetched successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function dropdown()
    {
        try {
            $this->permission->canViewShops();

            return $this->successResponse('Shop dropdown fetched successfully.', $this->shopService->dropdown());
        } catch (\Throwable $th) {
            return $this->errorResponse('Shop dropdown could not be fetched.', $th);
        }
    }
}
